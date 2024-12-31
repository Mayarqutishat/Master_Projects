@extends('layouts.master')

@section('title', 'User List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">My Profile: {{ $user->name }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <tr id="user-row-{{ $user->id }}" class="{{ $user->deleted_at ? 'text-muted' : '' }}">
                                <td>{{ $user->id }}</td>
                                <td>{{ e($user->name) }}</td>
                                <td>
                                    @if($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm view-details-btn" 
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}"
                                            data-role="{{ ucfirst($user->user_role) }}"
                                            data-gender="{{ ucfirst($user->gender) }}"
                                            data-age="{{ $user->age }}"
                                            data-phone="{{ $user->phone }}"
                                            data-address="{{ $user->address }}"
                                            data-created="{{ $user->created_at }}"
                                            data-updated="{{ $user->updated_at }}">
                                        View
                                    </button>
                                    @if($user->deleted_at)
                                        <button class="btn btn-danger btn-sm" disabled title="User is already deleted">Deleted</button>
                                    @else
                                        <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $user->id }}">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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

        // Handle soft delete for users
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the user!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/customer/users/${userId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                },
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'User has been soft deleted.', 'success');
                                    const row = document.querySelector(`#user-row-${userId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete user.', 'error');
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
