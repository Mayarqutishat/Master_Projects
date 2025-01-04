@extends('layouts.master')

@section('title', 'Images List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Images List</h4>

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
                                <tr id="image-row-{{ $image->id }}" class="{{ $image->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $image->id }}</td>
                                    <td>{{ $image->product_id }}</td>
                                    <td>{{ $image->url }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-btn" 
                                                data-id="{{ $image->id }}" 
                                                data-url="{{ $image->url }}" 
                                                data-product_id="{{ $image->product_id }}" 
                                                data-alt_text="{{ $image->alt_text }}"
                                                data-created_at="{{ $image->created_at }}"
                                                data-updated_at="{{ $image->updated_at }}">View</button>
                                        
                                        <button class="btn {{ $image->deleted_at ? 'btn-success' : 'btn-danger' }} btn-sm toggle-delete-btn" 
                                                data-id="{{ $image->id }}" 
                                                data-action="{{ $image->deleted_at ? 'restore' : 'delete' }}">
                                            {{ $image->deleted_at ? 'Restore' : 'Delete' }}
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

        // Handle Toggle Delete/Restore
        document.querySelectorAll('.toggle-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const imageId = button.getAttribute('data-id');
                const action = button.getAttribute('data-action');

                const confirmText = action === 'delete' 
                    ? 'Are you sure you want to soft delete this image?' 
                    : 'Are you sure you want to restore this image?';
                
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
                            ? `/admin/images/${imageId}/soft-delete` 
                            : `/admin/images/${imageId}/restore`;

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
                                    const row = document.querySelector(`#image-row-${imageId}`);
                                    if (action === 'delete') {
                                        row.classList.add('text-muted');
                                        button.innerText = 'Restore';
                                        button.classList.remove('btn-danger');
                                        button.classList.add('btn-success');
                                        button.setAttribute('data-action', 'restore');
                                    } else {
                                        row.classList.remove('text-muted');
                                        button.innerText = 'Delete';
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-danger');
                                        button.setAttribute('data-action', 'delete');
                                    }
                                    Swal.fire(action === 'delete' ? 'Deleted!' : 'Restored!', `Image has been ${action === 'delete' ? 'soft deleted' : 'restored'}.`, 'success');
                                } else {
                                    Swal.fire('Error', `Failed to ${action} the image.`, 'error');
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