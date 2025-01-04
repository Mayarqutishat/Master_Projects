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
                                        <button class="btn {{ $user->deleted_at ? 'btn-success restore-btn' : 'btn-danger delete-btn' }} btn-sm toggle-delete-btn" data-id="{{ $user->id }}" data-deleted="{{ $user->deleted_at ? 'true' : 'false' }}">
                                            {{ $user->deleted_at ? 'Restore' : 'Delete' }}
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
        {{ $users->links('vendor.pagination.custom') }}
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

        // Toggle between delete and restore
        document.querySelectorAll('.toggle-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');
                const isDeleted = button.getAttribute('data-deleted') === 'true';

                const action = isDeleted ? 'restore' : 'soft-delete';
                const actionText = isDeleted ? 'restore' : 'soft delete';
                const successMessage = isDeleted ? 'User has been restored.' : 'User has been soft deleted.';

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This action will ${actionText} the user!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${actionText} it!`
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/users/${userId}/${action}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Success!', successMessage, 'success');

                                    // Update the button and row appearance
                                    const row = document.querySelector(`#user-row-${userId}`);
                                    if (isDeleted) {
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-danger');
                                        button.innerText = 'Delete';
                                        button.setAttribute('data-deleted', 'false');
                                        row.classList.remove('text-muted');
                                    } else {
                                        button.classList.remove('btn-danger');
                                        button.classList.add('btn-success');
                                        button.innerText = 'Restore';
                                        button.setAttribute('data-deleted', 'true');
                                        row.classList.add('text-muted');
                                    }
                                }
                            }
                        } catch (error) {
                            Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush