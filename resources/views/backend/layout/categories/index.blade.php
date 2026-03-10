@extends('backend.master')
@section('title', 'Categories Management')

@section('content')
    <style>
        .category-card { border: none; border-radius: 12px; transition: all 0.3s ease; }
        .category-card:hover { transform: translateY(-3px); }
        .table-premium th { background-color: #f8f9fa; text-transform: uppercase; font-size: 11px; font-weight: 700; color: #495057; border-bottom: 2px solid #eff2f7; }
        .category-img { width: 45px; height: 45px; object-fit: cover; border-radius: 10px; border: 2px solid #f1f3f5; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .badge-soft-primary { background-color: rgba(75, 56, 179, 0.1); color: #4b38b3; }
        .badge-soft-success { background-color: rgba(10, 179, 156, 0.1); color: #0ab39c; }
        .badge-soft-danger { background-color: rgba(240, 101, 72, 0.1); color: #f06548; }
        .action-btn { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; }
        .modal-content { border: none; border-radius: 15px; overflow: hidden; }
        .modal-header { border-bottom: 1px solid #f1f3f5; }
        .form-label { font-weight: 600; color: #495057; font-size: 13px; }
        .image-preview-container { width: 100px; height: 100px; border: 2px dashed #ced4da; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; background: #f8f9fa; }
        .image-preview-container img { width: 100%; height: 100%; object-fit: cover; }
    </style>

    {{-- PAGE-HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-sm-0 fw-bold text-primary">Category Management</h4>
                    <p class="text-muted mb-0 fs-13">Organize and manage your product categories effortlessly.</p>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-12">
            <div class="card category-card shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed py-3">
                    <h4 class="card-title mb-0 flex-grow-1 fw-bold text-dark">
                        <i class="ri-list-check-2 me-2"></i> Category Directory
                    </h4>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-label waves-effect waves-light" onclick="openCreateModal()">
                            <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Add New Category
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-premium align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    {{-- <th>Image</th> --}}
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-soft-primary p-3">
                    <h5 class="modal-title fw-bold text-primary" id="categoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="categoryForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="category_id" name="id">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Category Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Smartphones" required>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fs-11">/categories/</span>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="smartphones" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label d-block">Status <span class="text-danger">*</span></label>
                                    <div class="btn-group w-100" role="group" aria-label="Status selection">
                                        <input type="radio" class="btn-check" name="status" id="status_active" value="1" checked required>
                                        <label class="btn btn-outline-success w-50 py-2" for="status_active">
                                            <i class="ri-checkbox-circle-line align-middle me-1"></i> Active
                                        </label>

                                        <input type="radio" class="btn-check" name="status" id="status_inactive" value="0" required>
                                        <label class="btn btn-outline-danger w-50 py-2" for="status_inactive">
                                            <i class="ri-close-circle-line align-middle me-1"></i> Inactive
                                        </label>
                                    </div>
                                </div>
                                {{-- <div class="mb-0">
                                    <label class="form-label">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <p class="text-muted fs-11 mt-1">Recommended: Square image (500x500px)</p>
                                </div> --}}
                            </div>
                            {{-- <div class="col-md-5 d-flex align-items-center justify-content-center">
                                <div class="image-preview-container">
                                    <img id="image_preview" src="https://via.placeholder.com/100?text=Preview" alt="Preview">
                                </div>
                            </div> --}}
                        </div>

                        <div class="mt-4 text-end border-top pt-3">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            // DataTable Initialization
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.feature.category.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-medium text-muted' },
                    // { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'title', name: 'title', class: 'fw-bold text-dark' },
                    { data: 'slug', name: 'slug', class: 'text-muted fs-13' },
                    { data: 'status', name: 'status', class: 'text-center', orderable: false, searchable: false },
                    { data: 'action', name: 'action', class: 'text-center', orderable: false, searchable: false }
                ],
                language: {
                    paginate: {
                        previous: '<i class="ri-arrow-left-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>'
                    }
                },
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });

            // Auto-Slug Generation
            $('#title').on('input', function() {
                if (!$('#category_id').val()) { // Only auto-generate for new entries
                    let slug = $(this).val().toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    $('#slug').val(slug);
                }
            });

            // Image Preview
            /*
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#image_preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
            */

            // Form Submission
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let id = $('#category_id').val();
                let url = id ? "{{ route('backend.feature.category.update', ':id') }}".replace(':id', id) : "{{ route('backend.feature.category.store') }}";
                
                if (id) formData.append('_method', 'PATCH');

                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#categoryModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).html('Save Category');
                    }
                });
            });
        });

        function openCreateModal() {
            $('#categoryForm')[0].reset();
            $('#category_id').val('');
            $('#categoryModalLabel').text('Add New Category');
            $('#status_active').prop('checked', true);
            // $('#image_preview').attr('src', 'https://via.placeholder.com/100?text=Preview');
            $('#categoryModal').modal('show');
        }

        function edit(id) {
            let url = "{{ route('backend.feature.category.edit', ':id') }}".replace(':id', id);
            $.get(url, function(response) {
                if (response.success) {
                    $('#category_id').val(response.category.id);
                    $('#title').val(response.category.title);
                    $('#slug').val(response.category.slug);
                    $(`input[name="status"][value="${response.category.status}"]`).prop('checked', true);
                    
                    // let imgSrc = response.category.image ? '/' + response.category.image : 'https://via.placeholder.com/100?text=Preview';
                    // $('#image_preview').attr('src', imgSrc);
                    
                    $('#categoryModalLabel').text('Edit Category: ' + response.category.title);
                    $('#categoryModal').modal('show');
                }
            });
        }

        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Update Status?',
                text: 'Are you sure you want to toggle this category status?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it',
                customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-light' }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('backend.feature.category.status', ':id') }}".replace(':id', id), { _token: "{{ csrf_token() }}" }, function(response) {
                        $('#datatable').DataTable().ajax.reload(null, false);
                        toastr.success(response.message);
                    });
                }
            });
        }

        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Delete Category?',
                text: 'This action is permanent and cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f06548',
                confirmButtonText: 'Yes, delete permanently',
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.feature.category.destroy', ':id') }}".replace(':id', id),
                        data: { _method: "DELETE", _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            $('#datatable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message);
                        }
                    });
                }
            });
        }
    </script>
@endpush
