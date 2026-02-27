@extends('backend.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="tasksList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">All Terms & Conditions</h5>
                        <div class="flex-shrink-0">
                            <a class="btn btn-danger add-btn" href="{{ route('backend.feature.terms.create') }}">
                                <i class="ri-add-line align-bottom me-1"></i> Create Terms
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Terms List</h5>
                    </div>

                    <div class="card-body">

                        <!-- Search Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="search-box position-relative">
                                    <input type="text" id="customSearch" class="form-control ps-5"
                                        placeholder="Search by title or description...">
                                    <i
                                        class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                </div>
                            </div>

                            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                <button class="btn btn-outline-secondary" id="refreshTable">
                                    <i class="ri-refresh-line align-bottom me-1"></i> Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive table-card mb-4">
                            <table class="table align-middle table-nowrap table-striped mb-0 data-table">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-top')
@endpush
@push('scripts-bottom')
    <script>
        (function($) {
            $(function() {

                let table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    dom: 'lrtip',

                    ajax: "{{ route('backend.feature.terms.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'priority',
                            name: 'priority'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $('#refreshTable').on('click', function() {
                    $('#customSearch').val('');
                    table.search('').draw();
                });

            });
        })(jQuery);

        // Status Toggle
        function statusTerm(id) {
            let url = "{{ route('backend.feature.terms.status', ':id') }}";

            $.post(url.replace(':id', id), {
                _token: "{{ csrf_token() }}"
            }, function(response) {

                if (response.success) {
                    $('.data-table').DataTable().ajax.reload();
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        // Edit
        function editTerm(id) {
            let url = "{{ route('backend.feature.terms.edit', ':id') }}";
            window.location.href = url.replace(':id', id);
        }

        // Delete
        function deleteData(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this Terms?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.data-table').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
