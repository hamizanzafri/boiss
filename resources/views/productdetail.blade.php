@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="product-detail row">
        <div class="col-md-6">
            <!-- Product Image Display -->
            <div class="product-image">
                <img class="d-block w-100" src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="price">RM{{ number_format($product->price, 2) }}</p>
            <div class="sizes">
                <label for="size">Size:</label>
                <div class="d-flex flex-wrap">
                    @foreach ($product->stocks as $stock)
                        <button class="btn btn-outline-primary m-1 size-button"
                                data-quantity="{{ $stock->quantity }}"
                                onclick="updateQuantity(this)">
                            {{ $stock->size }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="quantity mt-3">
                <label for="quantity">Stock Availability:</label>
                <span id="stock_status" class="form-control"></span>
            </div>
            <div class="description mt-3">
                <label for="size">Details:</label>
                <p>{{ $product->details }}</p>
            </div>
            <button type="button" class="btn btn-primary mt-3" id="addToCart">Add to cart</button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function updateQuantity(element) {
    $('.size-button').removeClass('selected-size'); // Remove the class from all buttons
    $(element).addClass('selected-size'); // Add the class to the clicked button

    var quantity = element.getAttribute('data-quantity');
    var stockStatus = document.getElementById('stock_status');
    stockStatus.textContent = quantity > 0 ? 'Item Available' : 'Item Unavailable';
    stockStatus.style.color = quantity > 0 ? 'green' : 'red';
    window.selectedSize = element.textContent.trim();
    window.selectedQuantity = parseInt(quantity); // Store selected quantity for use in add to cart
    window.selectedSize = $(element).text().trim(); // Ensure this line is capturing the text correctly
}

$('#addToCart').click(function() {
    var productId = '{{ $product->id }}';
    var size = window.selectedSize;
    var quantity = 1; // Adjust if you add a quantity input

    if (window.selectedQuantity && window.selectedQuantity > 0) {
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: productId,
                size: size,
                quantity: quantity,
                type: 'product' // Add a type to differentiate between products and events
            },
            success: function(response) {
                if(response.success) {
                    alert('Product added to cart!');
                    location.reload(); // Refresh page to update cart display
                } else {
                    alert(response.message || 'Unable to add product to cart.');
                }
            },
            error: function() {
                alert('Error adding product to cart. Please try again.');
            }
        });
    } else {
        alert('Item is out of stock and cannot be added to the cart.');
    }
});


</script>
<style>
.size-button.selected-size {
    background-color: #007bff; /* Bootstrap primary color for example */
    color: white;
}
</style>
@endsection
