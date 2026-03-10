@extends('backend.master')
@section('title', 'System User Management')

@section('content')
    <style>
        .user-card { border: none; border-radius: 12px; transition: all 0.3s ease; }
        .user-card:hover { transform: translateY(-3px); }
        .table-premium th { background-color: #f8f9fa; text-transform: uppercase; font-size: 11px; font-weight: 700; color: #495057; border-bottom: 2px solid #eff2f7; }
        .action-btn { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.2s; }
        .modal-content { border: none; border-radius: 15px; overflow: hidden; }
        .modal-header { border-bottom: 1px solid #f1f3f5; }
        .form-label { font-weight: 600; color: #495057; font-size: 13px; }
        .user-avatar-circle { width: 35px; height: 35px; border-radius: 50%; background-color: #eff2f7; display: flex; align-items: center; justify-content: center; color: #4b38b3; font-weight: 700; font-size: 14px; }
    </style>

    {{-- PAGE-HEADER --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-sm-0 fw-bold text-primary">System User Management</h4>
                    <p class="text-muted mb-0 fs-13">Manage administrative users and their access levels.</p>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">System Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-12">
            <div class="card user-card shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed py-3">
                    <h4 class="card-title mb-0 flex-grow-1 fw-bold text-dark">
                        <i class="ri-group-line me-2"></i> Administrative Directory
                    </h4>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-label waves-effect waves-light" onclick="openCreateModal()">
                            <i class="ri-user-add-line label-icon align-middle fs-16 me-2"></i> Add New User
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover table-premium align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>User Info</th>
                                    <th>Email Address</th>
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

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-soft-primary p-3">
                    <h5 class="modal-title fw-bold text-primary" id="userModalLabel">Add System User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="user_id" name="id">
                        <input type="hidden" name="is_admin_user" value="1">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter user's name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="user@example.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password <span class="text-danger" id="password_required_star">*</span></label>
                            <div class="position-relative auth-pass-inputgroup">
                                <input type="password" class="form-control pe-5 password-input" name="password" id="password" placeholder="Enter password">
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                            <small class="text-muted fs-11 mt-1 edit-note d-none">Leave blank to keep current password.</small>
                        </div>

                        <div class="mt-4 text-end border-top pt-3">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">Save User</button>
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
                ajax: "{{ route('backend.system-user.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'fw-medium text-muted' },
                    { 
                        data: 'name', 
                        name: 'name', 
                        render: function(data, type, row) {
                            let initial = data.charAt(0).toUpperCase();
                            let roleLabel = (row.role === 'admin' || row.is_admin_user == 1) ? 'Administrator' : 'User';
                            return `<div class="d-flex align-items-center">
                                        <div class="user-avatar-circle me-3">${initial}</div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">${data}</h6>
                                            <span class="text-muted fs-12">${roleLabel}</span>
                                        </div>
                                    </div>`;
                        }
                    },
                    { data: 'email', name: 'email', class: 'text-muted fs-13' },
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

            // Password Addon Toggle
            $('#password-addon').on('click', function() {
                let passInput = $('#password');
                if (passInput.attr('type') === 'password') {
                    passInput.attr('type', 'text');
                    $(this).find('i').removeClass('ri-eye-fill').addClass('ri-eye-off-fill');
                } else {
                    passInput.attr('type', 'password');
                    $(this).find('i').removeClass('ri-eye-off-fill').addClass('ri-eye-fill');
                }
            });

            // Form Submission
            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let id = $('#user_id').val();
                let url = id ? "{{ route('backend.system-user.update', ':id') }}".replace(':id', id) : "{{ route('backend.system-user.store') }}";
                
                if (id) formData.append('_method', 'PATCH');

                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#userModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload(null, false);
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error(xhr.responseJSON.message || 'Operation failed');
                        }
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).html('Save User');
                    }
                });
            });
        });

        function openCreateModal() {
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#userModalLabel').text('Add System User');
            $('#password_required_star').removeClass('d-none');
            $('#password').attr('required', true);
            $('.edit-note').addClass('d-none');
            $('#userModal').modal('show');
        }

        function edit(id) {
            let url = "{{ route('backend.system-user.edit', ':id') }}".replace(':id', id);
            $.get(url, function(response) {
                if (response.success) {
                    $('#user_id').val(response.user.id);
                    $('#name').val(response.user.name);
                    $('#email').val(response.user.email);
                    
                    $('#password_required_star').addClass('d-none');
                    $('#password').removeAttr('required');
                    $('.edit-note').removeClass('d-none');
                    
                    $('#userModalLabel').text('Edit System User: ' + response.user.name);
                    $('#userModal').modal('show');
                }
            });
        }

        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Change User Status?',
                text: 'Provide a reason for suspension (optional):',
                input: 'textarea',
                inputPlaceholder: 'Type your reason here...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-light' },
                preConfirm: (reason) => {
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('backend.system-user.status', ':id') }}".replace(':id', id), { 
                        _token: "{{ csrf_token() }}",
                        reason: result.value 
                    }, function(response) {
                        if (response.success) {
                            $('#datatable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }).fail(function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Action denied');
                    });
                }
            });
        }

        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Delete User?',
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
                        url: "{{ route('backend.system-user.destroy', ':id') }}".replace(':id', id),
                        data: { _method: "DELETE", _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            $('#datatable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message);
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message || 'Access denied');
                        }
                    });
                }
            });
        }
    </script>
@endpush
