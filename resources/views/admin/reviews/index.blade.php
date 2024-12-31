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
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                            @foreach($reviews as $review)
                                <tr id="review-row-{{ $review->id }}" class="{{ $review->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->user_id }}</td>
                                    <td>{{ $review->updated_at ? $review->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $review->id }}" data-product-id="{{ $review->product_id }}" data-rating="{{ $review->rating }}" data-comment="{{ $review->comment }}" data-created-at="{{ $review->created_at }}" data-updated-at="{{ $review->updated_at }}" data-deleted-at="{{ $review->deleted_at }}">View</button>
                                        <button class="btn btn-danger btn-sm action-btn" data-id="{{ $review->id }}">
                                            @if($review->deleted_at)
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

        // Handle soft delete and restore for reviews
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const reviewId = button.getAttribute('data-id');
                const action = button.innerText.toLowerCase(); // Delete or Restore

                const confirmText = action === 'delete' 
                    ? 'Are you sure you want to soft delete this review?'
                    : 'Are you sure you want to restore this review?';
                
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
                            ? `/admin/reviews/${reviewId}/soft-delete` 
                            : `/admin/reviews/${reviewId}/restore`;

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
                                    const row = document.querySelector(`#review-row-${reviewId}`);
                                    if (action === 'delete') {
                                        row.classList.add('text-muted');
                                        button.innerText = 'Restore';
                                        button.classList.remove('btn-danger');
                                        button.classList.add('btn-success');
                                    } else {
                                        row.classList.remove('text-muted');
                                        button.innerText = 'Delete';
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-danger');
                                    }
                                    Swal.fire(action === 'delete' ? 'Deleted!' : 'Restored!', `Review has been ${action === 'delete' ? 'soft deleted' : 'restored'}.`, 'success');
                                } else {
                                    Swal.fire('Error', `Failed to ${action} the review.`, 'error');
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
