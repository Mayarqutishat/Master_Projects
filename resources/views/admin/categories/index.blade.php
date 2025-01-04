@extends('layouts.master')

@section('title', 'Category List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Category List</h4>
                <button class="btn btn-success mb-3" id="addCategoryBtn">Add New Category</button>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                                <th>Category Image</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            @foreach($categories as $category)
                                <tr id="category-row-{{ $category->id }}">
                                    <td>{{ $category->id }}</td>
                                    <td class="category-name">{{ $category->name }}</td>
                                    <td class="category-image">
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" width="50">
                                    </td>
                                    <td>{{ $category->created_at }}</td>
                                    <td>{{ $category->updated_at }}</td>
                                    <td>
                                        @if($category->deleted_at)
                                            <button class="btn btn-info btn-sm restore-btn" data-id="{{ $category->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-warning btn-sm edit-btn" 
                                                    data-id="{{ $category->id }}" 
                                                    data-name="{{ $category->name }}" 
                                                    data-image="{{ $category->image }}">Edit</button>
                                            <button class="btn btn-danger btn-sm delete-btn" 
                                                    data-id="{{ $category->id }}">Delete</button>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Image</label>
                        <input type="file" class="form-control" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="editCategoryImage" name="image">
                    </div>
                    <button type="submit" class="btn btn-warning">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $categories->links('vendor.pagination.custom') }}
</div>

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const addModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));

    // Add Category
    document.getElementById('addCategoryBtn').addEventListener('click', () => addModal.show());

    document.getElementById('addCategoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('/admin/categories', {
                method: 'POST',
                body: new FormData(e.target),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            if (data.success) {
                addModal.hide();
                Swal.fire({
                    title: 'Added Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            }
        } catch (error) {
            Swal.fire('Error', 'An error occurred while adding the category', 'error');
        }
    });

    // Edit Category
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editCategoryId').value = btn.dataset.id;
            document.getElementById('editCategoryName').value = btn.dataset.name;
            editModal.show();
        });
    });

    document.getElementById('editCategoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const categoryId = document.getElementById('editCategoryId').value;
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`/admin/categories/${categoryId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                }
            });

            const data = await response.json();
            if (data.success) {
                editModal.hide();
                const row = document.getElementById(`category-row-${categoryId}`);
                row.querySelector('.category-name').textContent = data.category.name;
                
                // تحديث الصورة مباشرة في الجدول
                if (data.category.image) {
                    row.querySelector('.category-image img').src = data.category.image;
                }

                Swal.fire({
                    title: 'Updated Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            Swal.fire('Error', 'An error occurred while updating the category', 'error');
        }
    });

    // Soft Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be soft deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/categories/${btn.dataset.id}/soft-delete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        const row = document.getElementById(`category-row-${btn.dataset.id}`);
                        row.querySelector('td:last-child').innerHTML = `
                            <button class="btn btn-info btn-sm restore-btn" data-id="${btn.dataset.id}">Restore</button>
                        `;
                        initRestoreButtons();
                        Swal.fire('Deleted!', 'Category has been soft deleted', 'success');
                    }
                } catch (error) {
                    Swal.fire('Error', 'An error occurred while deleting the category', 'error');
                }
            }
        });
    });

    // Restore Category
    function initRestoreButtons() {
        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                try {
                    const response = await fetch(`/admin/categories/${btn.dataset.id}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        const row = document.getElementById(`category-row-${btn.dataset.id}`);
                        row.querySelector('td:last-child').innerHTML = `
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${btn.dataset.id}" data-name="${data.category.name}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${btn.dataset.id}">Delete</button>
                        `;
                        Swal.fire('Restored!', 'Category has been restored successfully', 'success');
                        location.reload();
                    }
                } catch (error) {
                    Swal.fire('Error', 'An error occurred while restoring the category', 'error');
                }
            });
        });
    }

    // Initialize restore buttons on page load
    initRestoreButtons();
});
</script>
@endpush