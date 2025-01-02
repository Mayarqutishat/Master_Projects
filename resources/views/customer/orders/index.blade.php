@extends('layouts.master')

@section('title', 'Order List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Order List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            @foreach($orders as $order)
                                <tr id="order-row-{{ $order->id }}" class="{{ $order->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user_id }}</td>
                                    <td>${{ number_format($order->total_price, 2) }}</td>
                                    <td>{{ $order->deleted_at ? 'Deleted' : ucfirst($order->status) }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" 
                                                data-id="{{ $order->id }}" 
                                                data-status="{{ ucfirst($order->status) }}" 
                                                data-location="{{ $order->shipping_location }}" 
                                                data-coupon="{{ $order->coupon_id ?? 'N/A' }}" 
                                                data-created="{{ $order->created_at }}" 
                                                data-updated="{{ $order->updated_at }}">View</button>
                                        
                                        @if($order->deleted_at)
                                            <button class="btn btn-success btn-sm restore-btn" data-id="{{ $order->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $order->id }}">Delete</button>
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
    {{ $orders->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View order details in SweetAlert
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const orderId = button.getAttribute('data-id');
                const status = button.getAttribute('data-status');
                const shippingLocation = button.getAttribute('data-location');
                const couponId = button.getAttribute('data-coupon');
                const createdAt = button.getAttribute('data-created');
                const updatedAt = button.getAttribute('data-updated');

                Swal.fire({
                    title: `Order #${orderId} Details`,
                    html: `
                        <p><strong>Status:</strong> ${status}</p>
                        <p><strong>Shipping Location:</strong> ${shippingLocation}</p>
                        <p><strong>Coupon ID:</strong> ${couponId}</p>
                        <p><strong>Created At:</strong> ${createdAt}</p>
                        <p><strong>Updated At:</strong> ${updatedAt}</p>
                    `,
                    icon: 'info',
                    showCloseButton: true,
                    confirmButtonText: 'Close'
                });
            });
        });

        // Soft delete order
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const orderId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the order!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/customer/orders/${orderId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (response.ok) {
                                Swal.fire('Deleted!', 'Order has been soft deleted.', 'success');
                                location.reload();
                            } else {
                                Swal.fire('Error', 'Failed to delete order.', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                        }
                    }
                });
            });
        });

        // Restore soft-deleted order
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const orderId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will restore the order!',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/customer/orders/${orderId}/restore`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                }
                            });

                            if (response.ok) {
                                Swal.fire('Restored!', 'Order has been restored.', 'success');
                                location.reload();
                            } else {
                                Swal.fire('Error', 'Failed to restore order.', 'error');
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
