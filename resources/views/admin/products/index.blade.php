@extends('layouts.master')

@section('title', 'Product List')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Product List</h4>
                    <button class="btn btn-success mb-3" id="addProductBtn">Add New Product</button>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                @foreach($products as $product)
                                    <tr id="product-row-{{ $product->id }}" class="{{ $product->deleted_at ? 'text-muted' : '' }}">
                                        <td>{{ $product->id }}</td>
                                        <td class="product-name">{{ $product->name }}</td>
                                        <td class="product-description">{{ $product->description }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-category="{{ $product->category_id }}" data-created="{{ $product->created_at }}" data-updated="{{ $product->updated_at }}">View</button>
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-category="{{ $product->category_id }}">Edit</button>
                                            @if($product->deleted_at)
                                                <button class="btn btn-success btn-sm action-btn" data-id="{{ $product->id }}">Restore</button>
                                            @else
                                                <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $product->id }}">Delete</button>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="addProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="addProductName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="addProductDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="addProductPrice" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="addProductStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductCategory" class="form-label">Category ID</label>
                            <input type="number" class="form-control" id="addProductCategory" name="category_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="productImage" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProductId" name="id">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editProductDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editProductStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label">Category</label>
                            <select class="form-control" id="editProductCategory" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $products->links('vendor.pagination.custom') }}
</div>



    
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Add product modal toggle
        document.getElementById('addProductBtn').addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('addProductModal')).show();
        });

        // View product details in SweetAlert
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productDescription = button.getAttribute('data-description');
                const productPrice = button.getAttribute('data-price');
                const productStock = button.getAttribute('data-stock');
                const productCategoryId = button.getAttribute('data-category');
                const productCreatedAt = button.getAttribute('data-created');
                const productUpdatedAt = button.getAttribute('data-updated');

                Swal.fire({
                    title: productName,
                    html: `
                        <p><strong>Description:</strong> ${productDescription}</p>
                        <p><strong>Price:</strong> $${productPrice}</p>
                        <p><strong>Stock:</strong> ${productStock}</p>
                        <p><strong>Category ID:</strong> ${productCategoryId}</p>
                        <p><strong>Created At:</strong> ${productCreatedAt}</p>
                        <p><strong>Updated At:</strong> ${productUpdatedAt}</p>
                    `,
                    icon: 'info',
                    showCloseButton: true,
                    confirmButtonText: 'Close'
                });
            });
        });

        // Edit product modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');
                const productDescription = button.getAttribute('data-description');
                const productPrice = button.getAttribute('data-price');
                const productStock = button.getAttribute('data-stock');
                const productCategoryId = button.getAttribute('data-category');

                document.getElementById('editProductId').value = productId;
                document.getElementById('editProductName').value = productName;
                document.getElementById('editProductDescription').value = productDescription;
                document.getElementById('editProductPrice').value = productPrice;
                document.getElementById('editProductStock').value = productStock;
                document.getElementById('editProductCategory').value = productCategoryId;

                new bootstrap.Modal(document.getElementById('editProductModal')).show();
            });
        });

        // Add product form submission
        document.getElementById('addProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                const response = await fetch('/admin/products', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Product added successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while adding the product.', 'error');
            }
        });

        // Edit product form submission
        document.getElementById('editProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            const productId = document.getElementById('editProductId').value;

            try {
                const response = await fetch(`/admin/products/${productId}`, {
                    method: 'PUT',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Product updated successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while updating the product.', 'error');
            }
        });

        // Soft delete product
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const productId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the product!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/products/${productId}/soft-delete`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Product has been soft deleted.', 'success');
                                    const row = document.querySelector(`#product-row-${productId}`);
                                    row.classList.add('text-muted');
                                    row.querySelector('.soft-delete-btn').setAttribute('disabled', 'true');
                                    row.querySelector('.soft-delete-btn').innerText = 'Deleted';
                                }
                            }
                        } catch (error) {
                            Swal.fire('Error', 'An error occurred while deleting the product.', 'error');
                        }
                    }
                });
            });
        });

        // Restore product
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const productId = button.getAttribute('data-id');
                const action = button.innerText.toLowerCase(); // Restore or Delete

                const confirmText = action === 'delete' 
                    ? 'Are you sure you want to soft delete this product?' 
                    : 'Are you sure you want to restore this product?';
                
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
                            ? `/admin/products/${productId}/soft-delete` 
                            : `/admin/products/${productId}/restore`;

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
                                    const row = document.querySelector(`#product-row-${productId}`);
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
                                    Swal.fire(action === 'delete' ? 'Deleted!' : 'Restored!', `Product has been ${action === 'delete' ? 'soft deleted' : 'restored'}.`, 'success');
                                }
                            } else {
                                Swal.fire('Error', `Failed to ${action} the product.`, 'error');
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