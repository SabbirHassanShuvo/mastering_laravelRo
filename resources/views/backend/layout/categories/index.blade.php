@extends('backend.master')
@section('title', 'Categories')

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">categories</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    Categories
                </li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-2">
                        <a href="{{ route('backend.feature.category.create') }}" class="btn btn-primary">+ Add Category</a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    {{-- <th>Image</th> --}}
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('backend.feature.category.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    // {
                    //     data: 'image',
                    //     name: 'image',
                    //     orderable: false,
                    //     searchable: false
                    // },
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
                    },
                ]
            });
        });

        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }
        // Status Change
        function statusChange(id) {
            let url = "{{ route('backend.feature.category.status', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Reloade DataTable
                    $('#datatable').DataTable().ajax.reload(null, false);
                    if (response.success === true) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message || 'Operation failed');
                    }
                },
                error: function(error) {
                    // location.reload();
                }
            });
        }

        // Delete Confirm Alert
        function showDeleteConfirm(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function edit(id) {
            let url = "{{ route('backend.feature.category.edit', ':id') }}";
            url = url.replace(':id', id);

            window.location.href = url;
        }


        // Delete Button
        function deleteItem(id) {

            let url = "{{ route('backend.feature.category.destroy', ':id') }}";
            let table = $('#datatable').DataTable();

            $.ajax({
                type: "POST", // change DELETE to POST
                url: url.replace(':id', id),
                data: {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {

                    if (response.success) {

                        // reload without resetting pagination
                        table.ajax.reload(null, false);

                        toastr.success(response.message);

                    } else {
                        toastr.error('Delete failed');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    toastr.error('CSRF error or server problem');
                }
            });
        }
    </script>
@endpush
