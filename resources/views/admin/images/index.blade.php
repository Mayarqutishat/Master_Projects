@extends('layouts.master')

@section('title', 'Images List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Images List</h4>
                <button class="btn btn-success mb-3" id="addImageBtn">Add Image</button> <!-- Add Button -->

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product ID</th>
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="imagesTableBody">
                            @foreach($images as $image)
                                <tr id="image-row-{{ $image->id }}">
                                    <td>{{ $image->id }}</td>
                                    <td>{{ $image->product_id }}</td>
                                    <td>{{ $image->url }}</td>
                                    <td>
                                        @if($image->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-primary btn-sm view-btn" 
                                                    data-id="{{ $image->id }}" 
                                                    data-url="{{ $image->url }}" 
                                                    data-product_id="{{ $image->product_id }}" 
                                                    data-alt_text="{{ $image->alt_text }} "
                                                    data-created_at="{{ $image->created_at }}"
                                                    data-updated_at="{{ $image->updated_at }}">View</button>
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $image->id }}" data-url="{{ $image->url }}" data-product_id="{{ $image->product_id }}">Edit</button>
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $image->id }}">Delete</button>
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

<!-- Modal for Adding/Editing Image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Add Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="imageForm" method="POST">
                    @csrf
                    <input type="hidden" id="imageId" name="imageId">
                    <div class="form-group">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" required>
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="text" class="form-control" id="url" name="url" required>
                    </div>
                    <div class="form-group">
                        <label for="alt_text">Alt Text</label>
                        <input type="text" class="form-control" id="alt_text" name="alt_text">
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveImageBtn">Save Image</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $images->links('vendor.pagination.custom') }}
</div>



@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View Image Details
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', () => {
                const imageId = button.getAttribute('data-id');
                const url = button.getAttribute('data-url');
                const productId = button.getAttribute('data-product_id');
                const altText = button.getAttribute('data-alt_text');
                const createdAt = button.getAttribute('data-created_at');
                const updatedAt = button.getAttribute('data-updated_at');

                Swal.fire({
                    title: `Image #${imageId} Details`,
                    html: `
                        <p><strong>Product ID:</strong> ${productId}</p>
                        <p><strong>Alt Text:</strong> ${altText}</p>
                        <p><strong>Created At:</strong> ${createdAt}</p>
                        <p><strong>Updated At:</strong> ${updatedAt}</p>
                    `,
                    icon: 'info',
                    showCloseButton: true,
                    confirmButtonText: 'Close'
                });
            });
        });

        // Handle Edit Image Button Click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const imageId = button.getAttribute('data-id');
                const productId = button.getAttribute('data-product_id');
                const url = button.getAttribute('data-url');
                
                document.getElementById('imageModalLabel').innerText = "Edit Image";
                document.getElementById('imageId').value = imageId;
                document.getElementById('product_id').value = productId;
                document.getElementById('url').value = url;
                $('#imageModal').modal('show');
            });
        });

        // Handle Add New Image Button Click
        document.getElementById('addImageBtn').addEventListener('click', () => {
            document.getElementById('imageModalLabel').innerText = "Add Image";
            document.getElementById('imageForm').reset();
            $('#imageModal').modal('show');
        });

        // Handle Save Image Form Submit
        document.getElementById('imageForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const imageId = document.getElementById('imageId').value;
            const method = imageId ? 'PUT' : 'POST'; // Choose method based on whether we're editing or adding

            fetch(imageId ? `/images/${imageId}` : '/images', {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#imageModal').modal('hide');
                    location.reload();
                } else {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire('Error', 'Something went wrong!', 'error');
            });
        });

        // Handle Soft Delete
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                const imageId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the image.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/images/${imageId}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`image-row-${imageId}`).remove();
                                Swal.fire('Deleted!', 'Your image has been deleted.', 'success');
                            } else {
                                Swal.fire('Failed!', 'Failed to delete image.', 'error');
                            }
                        });
                    }
                });
            });
        });
    });
</script>
@endpush
