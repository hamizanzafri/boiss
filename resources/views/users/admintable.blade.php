@extends('layouts.master')

@section('title', 'Admin Page')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-title', 'List of Admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="position-relative">
                <div class="modal-button mt-2">
                    <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"
                            onclick="window.location.href='{{ route('users.create') }}'">
                        <i class="mdi mdi-plus me-1"></i> Add New Admin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Admin Name</th>
            <th>Admin Email</th>
            <th>Admin Role</th>
            <th width="280px">Action</th>
        </tr>

        @php $count = 1; @endphp

        @foreach ($users as $s)
            @if ($s->user_type === 'admin')
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->email }}</td>
                    <td>
                        <form action="{{ route('users.updateRole', $s->id) }}" method="POST" id="roleForm-{{ $s->id }}">
                            @csrf
                            @method('PUT')
                            <select name="role" class="form-select" onchange="updateRole('{{ $s->id }}')">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $s->roles->contains('name', $role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('users.destroy', $s->id) }}" method="POST">
                            <a class="btn btn-primary" href="{{ route('users.show', $s->id) }}">Details</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        function updateRole(userId) {
            var form = document.getElementById('roleForm-' + userId);
            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Role updated successfully.');
                    location.reload();
                } else {
                    alert('An error occurred while updating the role.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the role.');
            });
        }
    </script>
@endsection
