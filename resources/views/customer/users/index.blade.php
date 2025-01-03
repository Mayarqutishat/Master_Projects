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
                                <th>Phone</th>
                                <th>Address</th>
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
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" id="user-details-btn" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ ucfirst($user->user_role) }}" data-gender="{{ ucfirst($user->gender) }}" data-age="{{ $user->age }}" data-phone="{{ $user->phone }}" data-address="{{ $user->address }}" data-created="{{ $user->created_at }}" data-updated="{{ $user->updated_at }}">
                                        View Details
                                    </button>

                                    <button class="btn btn-danger btn-sm" id="soft-delete-btn" data-id="{{ $user->id }}">
                                        Soft Delete
                                    </button>

                                    <button class="btn btn-success btn-sm" id="update-btn" data-id="{{ $user->id }}">
                                        Update Info
                                    </button>
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
        document.querySelectorAll('#user-details-btn').forEach(button => {
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
        document.querySelectorAll('#soft-delete-btn').forEach(button => {
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
                            const response = await fetch(`/customer/users/${userId}/softDelete`, {
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

        // Handle user info update
        document.querySelectorAll('#update-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const userId = button.getAttribute('data-id');

                // Fetch user details via AJAX
                try {
                    const response = await fetch(`/customer/users/${userId}/view-profile`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    });

                    if (response.ok) {
                        const user = await response.json();

                        // Display a form in SweetAlert to update user info
                        Swal.fire({
                            title: 'Update User Information',
                            html: `
                                <form id="update-user-form">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="${user.name}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="${user.email}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" id="phone" name="phone" class="form-control" value="${user.phone}" required pattern="\d{10}" title="Please enter exactly 10 digits">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" class="form-control" value="${user.address}" required>
                                    </div>
                                </form>
                            `,
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            preConfirm: async () => {
                                const form = document.getElementById('update-user-form');
                                const phoneInput = form.querySelector('#phone');

                                // Validate phone number length
                                if (phoneInput.value.length !== 10) {
                                    Swal.showValidationMessage('Phone number must be exactly 10 digits');
                                    return false;
                                }

                                const formData = new FormData(form);

                                try {
                                    const updateResponse = await fetch(`/customer/users/${userId}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            name: formData.get('name'),
                                            email: formData.get('email'),
                                            phone: formData.get('phone'),
                                            address: formData.get('address'),
                                        }),
                                    });

                                    if (updateResponse.ok) {
                                        Swal.fire('Updated!', 'User information has been updated.', 'success');

                                        // Update the DOM with the new data
                                        const row = document.querySelector(`#user-row-${userId}`);
                                        row.querySelector('td:nth-child(2)').innerText = formData.get('name');
                                        row.querySelector('td:nth-child(4)').innerText = formData.get('phone');
                                        row.querySelector('td:nth-child(5)').innerText = formData.get('address');

                                        // Update the data attributes of the "View Details" button
                                        const viewDetailsButton = row.querySelector('#user-details-btn');
                                        viewDetailsButton.setAttribute('data-name', formData.get('name'));
                                        viewDetailsButton.setAttribute('data-email', formData.get('email'));
                                        viewDetailsButton.setAttribute('data-phone', formData.get('phone'));
                                        viewDetailsButton.setAttribute('data-address', formData.get('address'));

                                    } else {
                                        Swal.fire('Error', 'Failed to update user information.', 'error');
                                    }
                                } catch (error) {
                                    Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                                }
                            }
                        });
                    } else {
                        Swal.fire('Error', 'Failed to fetch user details.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Failed to communicate with the server.', 'error');
                }
            });
        });
    });
</script>
