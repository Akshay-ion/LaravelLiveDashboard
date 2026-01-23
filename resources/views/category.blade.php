@extends('layout')
@section('content')
    <div class="d-flex justify-content-between">
        <h1>Category</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Launch demo modal
        </button>
    </div>
    <table class="table table-bordered mt-5" id="categoryTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="categoryId" name="category_id">
                <div class="form-group">
                    <label for="categoryName">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function () {

    const categoryTable = $('#categoryTable').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: "{{ route('getcategories') }}",
                                columns: [
                                    {
                                        data: null,
                                        orderable: false,
                                        searchable: false,
                                        render: function (data, type, row, meta) {
                                            return meta.row + meta.settings._iDisplayStart + 1;
                                        }
                                    },
                                    { data: 'name', name: 'name' },
                                    {
                                        data: 'id',
                                        orderable: false,
                                        searchable: false,
                                        render: function (id) {
                                            return `
                                                <button class="btn btn-sm btn-primary edit-btn" data-id="${id}">Edit</button>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="${id}">Delete</button>
                                            `;
                                        }
                                    }
                                ]
                            });

    const categoryFormModal = new bootstrap.Modal(document.getElementById('exampleModal'));

    window.submitForm = function () {
        let categoryId = $('#categoryId').val();
        let categoryName = $('#categoryName').val();

        if (categoryName === '') {
            sweetAlertMessage('error', 'Category name cannot be empty');
            return;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('category.store') }}",
            dataType: "json",
            data: {
                name: categoryName,
                category_id: categoryId,
            },
            success: function (response) {
                if (response.status === 200) {
                    sweetAlertMessage('success', response.message);
                    categoryTable.ajax.reload(null, false);
                }
                else{
                    sweetAlertMessage('error', response.message);
                }
            },
            error: function (xhr, status, error) {
                sweetAlertMessage('error', 'An error occurred while processing your request.');
                console.error(error);
            }
        }).then(() => {
            categoryTable.ajax.reload();
            categoryFormModal.hide();
            $('#categoryId').val('');
            $('#categoryName').val('');
        });
    };

});
</script>
@endpush

@endsection
