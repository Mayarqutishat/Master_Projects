@extends('layouts.master')

@section('title', 'Coupons List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Coupons List</h4>
                <button class="btn btn-success mb-3" id="addCouponBtn">Add Coupon</button>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Expiry Date</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="couponsTableBody">
                            @foreach($coupons as $coupon)
                                <tr id="coupon-row-{{ $coupon->id }}">
                                    <td>{{ $coupon->id }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount }}</td>
                                    <td>{{ $coupon->expiry_date }}</td>
                                    <td>{{ $coupon->deleted_at }}</td>
                                    <td>


                                        <!-- Toggle Delete/Restore Button -->
                                        <button class="btn {{ $coupon->deleted_at ? 'btn-warning' : 'btn-danger' }} btn-sm toggle-delete-btn" data-id="{{ $coupon->id }}">
                                            {{ $coupon->deleted_at ? 'Restore' : 'Delete' }}
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

<!-- Add/Edit Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Add/Edit Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="couponForm">
                    <input type="hidden" id="couponId" name="id">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" class="form-control" id="discount" name="discount" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCouponBtn">Save Coupon</button>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $coupons->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle Toggle Delete/Restore
        document.querySelectorAll('.toggle-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const couponId = button.getAttribute('data-id');
                const isDeleted = button.innerText.trim() === 'Delete';

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This action will ${isDeleted ? 'soft delete' : 'restore'} the coupon!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${isDeleted ? 'soft delete' : 'restore'} it!`,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const url = isDeleted ? `/admin/coupons/${couponId}/soft-delete` : `/admin/coupons/${couponId}/restore`;
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Success!', `Coupon has been ${isDeleted ? 'soft deleted' : 'restored'}.`, 'success');
                                    button.innerText = isDeleted ? 'Restore' : 'Delete';
                                    button.classList.toggle('btn-warning');
                                    button.classList.toggle('btn-danger');
                                } else {
                                    Swal.fire('Error', 'Failed to perform the action.', 'error');
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

        // Handle Edit Button
        document.querySelectorAll('.edit-coupon-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const couponId = button.getAttribute('data-id');

                try {
                    const response = await fetch(`/admin/coupons/${couponId}/edit`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        document.getElementById('couponId').value = data.id;
                        document.getElementById('code').value = data.code;
                        document.getElementById('discount').value = data.discount;
                        document.getElementById('expiry_date').value = data.expiry_date;
                        document.getElementById('couponModalLabel').innerText = 'Edit Coupon';
                        $('#couponModal').modal('show');
                    } else {
                        Swal.fire('Error', 'Failed to fetch coupon details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to fetch coupon details.', 'error');
                }
            });
        });

        // Handle Save Button
        document.getElementById('saveCouponBtn').addEventListener('click', async () => {
            const formData = new FormData(document.getElementById('couponForm'));
            const couponId = formData.get('id');
            const method = couponId ? 'PUT' : 'POST';
            const url = couponId ? `/admin/coupons/${couponId}` : '/admin/coupons';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    Swal.fire('Success', 'Coupon has been saved.', 'success');
                    $('#couponModal').modal('hide');
                    location.reload();
                } else {
                    Swal.fire('Error', 'Failed to save coupon.', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network error. Failed to save coupon.', 'error');
            }
        });

        // Handle Add Button
        document.getElementById('addCouponBtn').addEventListener('click', () => {
            document.getElementById('couponForm').reset();
            document.getElementById('couponId').value = '';
            document.getElementById('couponModalLabel').innerText = 'Add Coupon';
            $('#couponModal').modal('show');
        });
    });
</script>
@endpush