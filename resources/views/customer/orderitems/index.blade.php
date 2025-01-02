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
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTableBody">
                            @foreach($orderItems as $item)
                                <tr id="order-item-row-{{ $item->id }}" 
                                    class="{{ $item->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->product_id }}</td>
                                    <td>
                                        {{ $item->deleted_at ? 'Deleted' : 'Active' }}
                                    </td>
                                    <td>
                                        <!-- عرض التفاصيل -->
                                        <button class="btn btn-primary btn-sm view-details-btn" 
                                            data-id="{{ $item->id }}" 
                                            data-quantity="{{ $item->quantity }}" 
                                            data-price="{{ $item->price }}" 
                                            data-deleted-at="{{ $item->deleted_at ?? 'N/A' }}" 
                                            data-created-at="{{ $item->created_at }}" 
                                            data-updated-at="{{ $item->updated_at }}">
                                            View
                                        </button>

                                        @if($item->deleted_at)
                                            <!-- استعادة العنصر -->
                                            <button class="btn btn-success btn-sm restore-btn" 
                                                data-id="{{ $item->id }}">
                                                Restore
                                            </button>
                                        @else
                                            <!-- حذف ناعم -->
                                            <button class="btn btn-danger btn-sm soft-delete-btn" 
                                                data-id="{{ $item->id }}">
                                                Delete
                                            </button>
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

<!-- روابط التصفح -->
<div class="d-flex justify-content-center">
    {{ $orderItems->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // عرض تفاصيل العنصر
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
                    confirmButtonText: 'Close'
                });
            });
        });

        // حذف ناعم
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const id = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the order item!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await handleAction(`/customer/order_items/${id}/soft-delete`, 'POST', button, 'Soft deleted');
                    }
                });
            });
        });

        // استعادة العنصر
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const id = button.getAttribute('data-id');
                await handleAction(`/customer/order_items/${id}/restore`, 'POST', button, 'Restored');
            });
        });

        // دالة مساعدة للتعامل مع الطلبات
        async function handleAction(url, method, button, successMessage) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                if (response.ok) {
                    Swal.fire('Success', successMessage, 'success');
                    location.reload(); // تحديث الصفحة بعد الإجراء
                } else {
                    Swal.fire('Error', 'Failed to perform the action.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Please try again later.', 'error');
            }
        }
    });
</script>
@endpush
