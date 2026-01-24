@extends('layout')
@section('content')
    <div class="d-flex justify-content-between">
        <h1>Product</h1>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
            Add Product
        </button>
    </div>
    <table class="table table-bordered mt-5" id="productTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="productModalLabel">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="productId" name="product_id">
                <div class="form-group">
                    <label for="categorySelect">Category</label>
                    <select class="form-select" id="categorySelect" name="category_id">
                        <option value="" selected disabled>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="name" placeholder="Enter product name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()" id="formButton">Save</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function () {

    const productTable = $('#productTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: "{{ route('getproducts') }}",
                                columns: [
                                    {
                                        data: null,
                                        orderable: false,
                                        searchable: false,
                                        render: function (data, type, row, meta) {
                                            return meta.row + meta.settings._iDisplayStart + 1;
                                        }
                                    },
                                    { data: 'category_name', name: 'category_name' },
                                    { data: 'name', name: 'name' },
                                    {
                                        data: 'id',
                                        orderable: false,
                                        searchable: false,
                                        render: function (id, type, row) {
                                            return `
                                               <button class="btn btn-sm btn-primary"
                                                    onclick="editProduct(
                                                    ${row.id},
                                                    '${row.name.replace(/'/g, "\\'")}',
                                                    ${row.category_id}
                                                )">
                                                    Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="${id}" onclick="deleteProduct(${id})">
                                                    Delete
                                                </button>
                                            `;
                                        }
                                    }
                                ]
                            });

    const productFormModal = new bootstrap.Modal(document.getElementById('productModal'));
    
    window.submitForm = function () {
        let productId = $('#productId').val();
        let productName = $('#productName').val();
        let category = $('#categorySelect').val();

        if (productName === '') {
            sweetAlertMessage('error', 'Product name cannot be empty');
            return;
        }
        if (!category) {
            sweetAlertMessage('error', 'Please select a category');
            return;
        }

        let buttonText = productId ? 'Updating...' : 'Saving...';
        $('#formButton').text(buttonText);
        $('#formButton').attr('disabled', true);

        $.ajax({
            type: "POST",
            url: "{{ route('product.store') }}",
            dataType: "json",
            data: {
                name: productName,
                category_id: category,
                product_id: productId,
            },
            success: function (response) {
                if (response.status === 200) {
                    sweetAlertMessage('success', response.message);
                    productTable.ajax.reload(null, false);
                }
                else{
                    sweetAlertMessage('error', response.message, true);
                }
            },
            error: function (xhr, status, error) {
                sweetAlertMessage('error', 'An error occurred while processing your request.', true);
                console.error(error);
            }
        }).always(() => {
            productTable.ajax.reload();
            productFormModal.hide();
            $('#productId').val('');
            $('#productName').val('');
            $('#categorySelect').val('');
            $('#productModalLabel').text('Add Product');
            $('#formButton').text('Save');
            $('#formButton').attr('disabled', false);
        });
    };

    window.editProduct = function (id, name, categoryId) {

        $('#productModalLabel').text('Edit Product');
        $('#formButton').text('Update');

        $('#productId').val(id);
        $('#productName').val(name);
        $('#categorySelect').val(categoryId).trigger('change');

        productFormModal.show();
    };

    window.deleteProduct = function (id) {
        let route = "{{ url('product') }}/" + id;
        sweetAlertDelete(route, productTable);
    };

});
</script>
@endpush

@endsection
