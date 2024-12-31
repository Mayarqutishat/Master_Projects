@extends('layouts.master')

@section('title', 'Reviews List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Reviews List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Updated At</th> <!-- Added Updated At column -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                            @foreach($reviews as $review)
                                <tr id="review-row-{{ $review->id }}">
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->user_id }}</td>
                                    <td>{{ $review->updated_at ? $review->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td> <!-- Display updated_at -->
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $review->id }}" data-product-id="{{ $review->product_id }}" data-rating="{{ $review->rating }}" data-comment="{{ $review->comment }}" data-created-at="{{ $review->created_at }}" data-updated-at="{{ $review->updated_at }}" data-deleted-at="{{ $review->deleted_at }}">View</button>
                                        @if($review->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $review->id }}">Delete</button>
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
    {{ $reviews->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Show review details in a popup (Swal)
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const reviewId = button.getAttribute('data-id');
                const productId = button.getAttribute('data-product-id');
                const rating = button.getAttribute('data-rating');
                const comment = button.getAttribute('data-comment');
                const createdAt = button.getAttribute('data-created-at');
                const updatedAt = button.getAttribute('data-updated-at') || 'N/A'; // Add updated_at
                const deletedAt = button.getAttribute('data-deleted-at') || 'N/A';

                Swal.fire({
                    title: `Review Details - ID: ${reviewId}`,
                    html: `
                        <ul>
                            <li><strong>Product ID:</strong> ${productId}</li>
                            <li><strong>Rating:</strong> ${rating}</li>
                            <li><strong>Comment:</strong> ${comment}</li>
                            <li><strong>Created At:</strong> ${createdAt}</li>
                            <li><strong>Updated At:</strong> ${updatedAt}</li> <!-- Show updated_at -->
                            <li><strong>Deleted At:</strong> ${deletedAt}</li>
                        </ul>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            });
        });

        // Handle soft delete for reviews
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const reviewId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the review!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/reviews/${reviewId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Review has been soft deleted.', 'success');
                                    const row = document.querySelector(`#review-row-${reviewId}`);
                                    row.classList.add('text-muted');
                                    button.disabled = true;
                                    button.innerText = 'Deleted';
                                } else {
                                    Swal.fire('Error', 'Failed to delete review.', 'error');
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
