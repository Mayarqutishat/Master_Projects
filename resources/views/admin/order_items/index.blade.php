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
                                <tr id="order-item-row-{{ $item->id }}" class="{{ $item->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->product_id }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $item->id }}" data-quantity="{{ $item->quantity }}" data-price="{{ $item->price }}" data-deleted-at="{{ $item->deleted_at ?? 'N/A' }}" data-created-at="{{ $item->created_at }}" data-updated-at="{{ $item->updated_at }}">View</button>
                                        <button class="btn {{ $item->deleted_at ? 'btn-danger' : 'btn-danger' }} btn-sm action-btn" data-id="{{ $item->id }}">
                                            @if($item->deleted_at)
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
    {{ $orderItems->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View order item details in SweetAlert
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const itemId = button.getAttribute('data-id');
                const quantity = button.getAttribute('data-quantity');
                const price = button.getAttribute('data-price');
                const deletedAt = button.getAttribute('data-deleted-at');
                const createdAt = button.getAttribute('data-created-at');
                const updatedAt = button.getAttribute('data-updated-at');

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

        // Handle delete and restore actions
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const itemId = button.getAttribute('data-id');
                const action = button.innerText.toLowerCase(); // Delete or Restore

                const confirmText = action === 'delete' 
                    ? 'Are you sure you want to soft delete this order item?'
                    : 'Are you sure you want to restore this order item?';
                
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
                            ? `/admin/order_items/${itemId}/soft-delete` 
                            : `/admin/order_items/${itemId}/restore`;

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
                                    const row = document.querySelector(`#order-item-row-${itemId}`);
                                    if (action === 'delete') {
                                        row.classList.add('text-muted');
                                        button.innerText = 'Restore';
                                        button.classList.remove('btn-danger'); // Remove red class for delete
                                        button.classList.add('btn-danger'); // Apply red class for restore
                                    } else {
                                        row.classList.remove('text-muted');
                                        button.innerText = 'Delete';
                                        button.classList.remove('btn-danger'); // Remove red class for restore
                                        button.classList.add('btn-danger'); // Apply red class for delete
                                    }
                                    Swal.fire(action === 'delete' ? 'Deleted!' : 'Restored!', `Order item has been ${action === 'delete' ? 'soft deleted' : 'restored'}.`, 'success');
                                } else {
                                    Swal.fire('Error', `Failed to ${action} the order item.`, 'error');
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
