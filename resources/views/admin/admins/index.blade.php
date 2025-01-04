@extends('layouts.master')

@section('title', 'Admin List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody">
                            @foreach($admins as $admin)
                                <tr id="admin-row-{{ $admin->id }}" class="{{ $admin->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <button 
                                            class="btn btn-primary btn-sm view-details-btn" 
                                            data-id="{{ $admin->id }}"
                                            data-name="{{ $admin->name }}"
                                            data-email="{{ $admin->email }}"
                                            data-created-at="{{ $admin->created_at }}"
                                            data-updated-at="{{ $admin->updated_at }}"
                                        >View</button>
                                        <button 
                                            class="btn {{ $admin->deleted_at ? 'btn-success restore-btn' : 'btn-danger soft-delete-btn' }}" 
                                            data-id="{{ $admin->id }}"
                                        >
                                            {{ $admin->deleted_at ? 'Restore' : 'Delete' }}
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
    {{ $admins->links('vendor.pagination.custom') }}
</div>

<!-- Modal for View Details -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Admin Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="modalAdminId"></span></p>
                <p><strong>Name:</strong> <span id="modalAdminName"></span></p>
                <p><strong>Email:</strong> <span id="modalAdminEmail"></span></p>
                <p><strong>Created At:</strong> <span id="modalAdminCreatedAt"></span></p>
                <p><strong>Updated At:</strong> <span id="modalAdminUpdatedAt"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Handle soft delete and restore for admins
    document.querySelectorAll('.soft-delete-btn, .restore-btn').forEach(button => {
        button.addEventListener('click', async () => {
            const adminId = button.getAttribute('data-id');
            const isRestore = button.classList.contains('restore-btn');

            Swal.fire({
                title: 'Are you sure?',
                text: isRestore ? 'This action will restore the admin!' : 'This action will soft delete the admin!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: isRestore ? 'Yes, restore it!' : 'Yes, soft delete it!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const url = isRestore 
                            ? `/admin/admins/${adminId}/restore` 
                            : `/admin/admins/${adminId}/soft-delete`;

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
                                Swal.fire('Success!', isRestore ? 'Admin has been restored.' : 'Admin has been soft deleted.', 'success');
                                
                                // تحديث واجهة المستخدم
                                const row = document.querySelector(`#admin-row-${adminId}`);
                                row.classList.toggle('text-muted');
                                
                                // تبديل الفئات والنص على الزر
                                button.classList.toggle('btn-danger');
                                button.classList.toggle('btn-success');
                                button.classList.toggle('soft-delete-btn');
                                button.classList.toggle('restore-btn');
                                button.innerText = isRestore ? 'Delete' : 'Restore';
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

    // Handle View button click to show details in a modal
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', () => {
            const adminId = button.getAttribute('data-id');
            const adminName = button.getAttribute('data-name');
            const adminEmail = button.getAttribute('data-email');
            const adminCreatedAt = button.getAttribute('data-created-at');
            const adminUpdatedAt = button.getAttribute('data-updated-at');

            // Populate the modal with the admin details
            document.getElementById('modalAdminId').textContent = adminId;
            document.getElementById('modalAdminName').textContent = adminName;
            document.getElementById('modalAdminEmail').textContent = adminEmail;
            document.getElementById('modalAdminCreatedAt').textContent = adminCreatedAt;
            document.getElementById('modalAdminUpdatedAt').textContent = adminUpdatedAt;

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
            modal.show();
        });
    });
});
</script>
@endpush