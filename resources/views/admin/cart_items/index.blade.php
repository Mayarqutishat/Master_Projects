@extends('layouts.master')

@section('title', 'Cart Items List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cart Items List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cart ID</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cartItemsTableBody">
                            @foreach($cartItems as $cartItem)
                                <tr id="cart-item-row-{{ $cartItem->id }}" class="{{ $cartItem->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $cartItem->id }}</td>
                                    <td>{{ $cartItem->cart_id }}</td>
                                    <td>{{ $cartItem->product_id }}</td>
                                    <td>{{ $cartItem->quantity }}</td>
                                    <td>
                                        <button class="btn {{ $cartItem->deleted_at ? 'btn-success' : 'btn-danger' }} btn-sm action-btn" data-id="{{ $cartItem->id }}">
                                            @if($cartItem->deleted_at)
                                                Restore
                                            @else
                                                Delete
                                            @endif
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $cartItems->links('vendor.pagination.custom') }}
</div>




@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle delete and restore actions
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const cartItemId = button.getAttribute('data-id');
                const action = button.innerText.toLowerCase(); // Delete or Restore

                const confirmText = action === 'delete' 
                    ? 'Are you sure you want to soft delete this cart item?' 
                    : 'Are you sure you want to restore this cart item?';
                
                const confirmButtonText = action === 'delete' 
                    ? 'Yes, soft delete it!' 
                    : 'Yes, restore it!';

                Swal.fire({
                    title: 'Are you sure?',
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const url = action === 'delete' 
                            ? `/admin/cart-items/${cartItemId}/soft-delete` 
                            : `/admin/cart-items/${cartItemId}/restore`;

                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    const row = document.querySelector(`#cart-item-row-${cartItemId}`);
                                    if (action === 'delete') {
                                        row.classList.add('text-muted');
                                        button.innerText = 'Restore';
                                        button.classList.remove('btn-danger');
                                        button.classList.add('btn-success');
                                    } else {
                                        row.classList.remove('text-muted');
                                        button.innerText = 'Delete';
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-danger');
                                    }
                                    Swal.fire(action === 'delete' ? 'Deleted!' : 'Restored!', `Cart item has been ${action === 'delete' ? 'soft deleted' : 'restored'}.`, 'success');
                                } else {
                                    Swal.fire('Error', `Failed to ${action} the cart item.`, 'error');
                                }
                            } else {
                                Swal.fire('Error', 'Failed to communicate with the server.', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
