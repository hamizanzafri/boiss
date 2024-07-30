@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="row">
        <h2>Cart</h2>
        <div class="col-md-6">
            <!-- Delivery Details Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Delivery Details</h2>
                    <form action="{{ route('payment') }}" method="post" id="payment-form">
                        @csrf
                        <!-- Form fields for delivery details -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                        </div>
                        @php
                            $address = auth()->user()->membership->address ?? '';
                            $addressParts = explode(', ', $address);
                            $street = $addressParts[0] ?? '';
                            $city = $addressParts[1] ?? '';
                            $state = $addressParts[2] ?? '';
                            $postcode = $addressParts[3] ?? '';
                        @endphp
                        <div class="mb-3">
                            <label for="street" class="form-label">Street Line 1:</label>
                            <input type="text" class="form-control" id="street" name="street" value="{{ $street }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City:</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ $city }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="postcode" class="form-label">Postcode:</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ $postcode }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State:</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ $state }}" required>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Order Details Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Your Order</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse (session('cart', []) as $id => $details)
                        <tr id="item-{{ $id }}">
                            <td>
                                {{ $details['name'] }}{{ isset($details['size']) ? ' - ' . $details['size'] : '' }}
                            </td>
                            <td>
                                <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity-input d-inline-block" style="width:60px;" data-id="{{ $id }}">
                            </td>
                            <td>RM {{ number_format($details['price'], 2) }}</td>
                            <td>RM {{ number_format($details['quantity'] * $details['price'], 2) }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Your cart is empty</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="text-right">
                        <h3>Subtotal: RM <span id="subtotal">{{ number_format($total, 2) }}</span></h3>
                        @if (auth()->user() && auth()->user()->hasMembership())
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>Membership Discount (10% on products):</strong>
                                <span class="badge bg-primary">
                                    RM <span id="membership-discount">{{ number_format($productTotal * 0.1, 2) }}</span>
                                </span>
                            </div>
                            <h3>Total After Discount: RM <span id="total-after-discount">{{ number_format(($total - $productTotal * 0.1), 2) }}</span></h3>
                        @else
                            <div class="mb-3">
                                <label for="voucher" class="form-label">Voucher Code:</label>
                                <input type="text" class="form-control" id="voucher" name="voucher" placeholder="Enter voucher code">
                                <button type="button" class="btn btn-primary mt-2" id="apply-voucher">Apply Voucher</button>
                                <div id="voucher-error" class="text-danger mt-2" style="display:none;">Invalid Voucher Code</div>
                                <div id="voucher-success" class="text-success mt-2" style="display:none;">Voucher Applied</div>
                            </div>
                            <h3>Total: RM <span id="total">{{ number_format($total, 2) }}</span></h3>
                        @endif
                        <input type="hidden" id="payment-amount" name="amount" value="{{ number_format(($total - $productTotal * 0.1), 2) }}">
                        <button type="submit" class="btn btn-success">Pay With PayPal</button>
                    </div>
                    </form> <!-- Close form here to encapsulate all inputs and the submit button -->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.quantity-input').on('change keyup', function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var quantity = parseInt($(this).val()) || 1; // Default to 1 if invalid number
        var pricePerUnit = parseFloat(row.find('td:eq(2)').text().replace('RM ', ''));
        var subtotal = quantity * pricePerUnit;
        row.find('.item-subtotal').text(`RM ${subtotal.toFixed(2)}`);

        // Update the session with the new quantity
        updateCartQuantity(id, quantity);

        updateTotal();
    });

    $('.remove-from-cart').on('click', function() {
        var id = $(this).data('id');
        removeItem(id, $(this).closest('tr'));
    });

    function removeItem(id, row) {
        $.ajax({
            url: `/cart/remove/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    row.remove();
                    updateTotal();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Error removing item.');
            }
        });
    }

    function updateCartQuantity(id, quantity) {
        $.ajax({
            url: `/cart/update/${id}`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Error updating item quantity.');
            }
        });
    }

    function updateTotal() {
        var total = 0;
        var productTotal = 0;
        $('.item-subtotal').each(function() {
            var subtotal = parseFloat($(this).text().replace('RM ', ''));
            total += subtotal;
            if (!$(this).closest('tr').find('td:first').text().includes('Ticket')) {
                productTotal += subtotal; // Only add to product total if it's not a ticket
            }
        });

        $('#subtotal').text(total.toFixed(2));
        var discount = 0;
        if ('{{ auth()->user() && auth()->user()->hasMembership() }}') {
            discount = productTotal * 0.1; // Apply discount only to product total
        }
        $('#membership-discount').text(discount.toFixed(2));
        $('#total-after-discount').text((total - discount).toFixed(2));
        $('#payment-amount').val((total - discount).toFixed(2)); // Update payment amount
    }

    $('#apply-voucher').click(function() {
        var voucherCode = $('#voucher').val();

        $.ajax({
            url: '{{ route("vouchers.apply") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: voucherCode
            },
            success: function(response) {
                if (response.success) {
                    $('#voucher-success').show();
                    $('#voucher-error').hide();
                    updateTotalWithVoucher(response.discount);
                } else {
                    $('#voucher-error').show();
                    $('#voucher-success').hide();
                }
            },
            error: function(xhr) {
                $('#voucher-error').show();
                $('#voucher-success').hide();
            }
        });
    });

    function updateTotalWithVoucher(discount) {
        var total = parseFloat($('#total').text());
        var newTotal = total - (total * (discount / 100));
        $('#total').text(newTotal.toFixed(2));
        $('#payment-amount').val(newTotal.toFixed(2)); // Update payment amount
    }
});
</script>

@endsection
