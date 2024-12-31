@extends('layouts.master')

@section('title', 'User List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach($users as $user)
                                <tr id="user-row-{{ $user->id }}" class="{{ $user->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $user->id }}</td>
                                    <td class="user-name">{{ $user->name }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ ucfirst($user->user_role) }}" data-gender="{{ ucfirst($user->gender) }}" data-age="{{ $user->age }}" data-phone="{{ $user->phone }}" data-address="{{ $user->address }}" data-created="{{ $user->created_at }}" data-updated="{{ $user->updated_at }}">View</button>
                                        @if($user->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $user->id }}">Delete</button>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="addUserName" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="addUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addUserRole" class="form-label">Role</label>
                        <input type="text" class="form-control" id="addUserRole" name="user_role" required>
                    </div>
                    <div class="mb-3">
                        <label for="addUserPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="addUserPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="addUserGender" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="addUserGender" name="gender" required>
                    </div>
                    <button type="submit" class="btn btn-success">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View user details in SweetAlert
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-id');
                const userName = button.getAttribute('data-name');
                const userEmail = button.getAttribute('data-email');
                const userRole = button.getAttribute('data-role');
                const userGender = button.getAttribute('data-gender');
                const userAge = button.getAttribute('data-age');
                const userPhone = button.getAttribute('data-phone');
                const userAddress = button.getAttribute('data-address');
                const userCreatedAt = button.getAttribute('data-created');
                const userUpdatedAt = button.getAttribute('data-updated');

                Swal.fire({
                    title: userName,
                    html: `
                        <p><strong>Email:</strong> ${userEmail}</p>
                        <p><strong>Role:</strong> ${userRole}</p>
                        <p><strong>Gender:</strong> ${userGender}</p>
                        <p><strong>Age:</strong> ${userAge}</p>
                        <p><strong>Phone:</strong> ${userPhone}</p>
                        <p><strong>Address:</strong> ${userAddress}</p>
                        <p><strong>Created At:</strong> ${userCreatedAt}</p>
                        <p><strong>Updated At:</strong> ${userUpdatedAt}</p>
                    `,
                    icon: 'info',
                    showCloseButton: true,
                    confirmButtonText: 'Close'
                });
            });
        });

        // Soft delete user
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the user!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/users/${userId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'User has been soft deleted.', 'success');
                                    const row = document.querySelector(`#user-row-${userId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                }
                            }
                        } catch (error) {
                            Swal.fire('Error', 'An error occurred while deleting the user.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush

