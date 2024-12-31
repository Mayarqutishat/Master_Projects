@extends('layouts.master')

@section('title', 'Order Items List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Order Items List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Product ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTableBody">
                            @foreach($orderItems as $item)
                                <tr id="order-item-row-{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->product_id }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $item->id }}" data-quantity="{{ $item->quantity }}" data-price="{{ $item->price }}" data-deleted-at="{{ $item->deleted_at ?? 'N/A' }}" data-created-at="{{ $item->created_at }}" data-updated-at="{{ $item->updated_at }}">View</button>
                                        @if($item->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $item->id }}">Delete</button>
                                        @endif
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
    {{ $orderItems->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle view details for order items in a popup
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const itemId = button.getAttribute('data-id');
                const quantity = button.getAttribute('data-quantity');
                const price = button.getAttribute('data-price');
                const deletedAt = button.getAttribute('data-deleted-at');
                const createdAt = button.getAttribute('data-created-at');
                const updatedAt = button.getAttribute('data-updated-at');

                // Show popup with order item details
                Swal.fire({
                    title: 'Order Item Details',
                    html: `
                        <div style="font-size: 16px;">
                            <ul>
                                <li><strong>ID:</strong> ${itemId}</li>
                                <li><strong>Quantity:</strong> ${quantity}</li>
                                <li><strong>Price:</strong> ${price}</li>
                                <li><strong>Deleted At:</strong> ${deletedAt}</li>
                                <li><strong>Created At:</strong> ${createdAt}</li>
                                <li><strong>Updated At:</strong> ${updatedAt}</li>
                            </ul>
                        </div>
                    `,
                    icon: 'info',
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Close'
                });
            });
        });

        // Handle soft delete for order items
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const orderItemId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the order item!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/order_items/${orderItemId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Order item has been soft deleted.', 'success');
                                    const row = document.querySelector(`#order-item-row-${orderItemId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete order item.', 'error');
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
