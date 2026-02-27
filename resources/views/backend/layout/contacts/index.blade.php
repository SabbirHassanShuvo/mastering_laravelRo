@extends('backend.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Contact Messages</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        (function($) {
            $(function() {

                let table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('feature.contacts.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'subject',
                            name: 'subject'
                        },
                        {
                            data: 'message',
                            name: 'message'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                // Mark as read
                window.markRead = function(id) {
                    $.post("{{ route('feature.contacts.markRead', '') }}/" + id, {
                            _token: "{{ csrf_token() }}"
                        },
                        function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                alert(response.message);
                            }
                        });
                }

                // View Contact
                window.viewContact = function(id) {
                    $.get("{{ route('feature.contacts.view', '') }}/" + id, function(response) {
                        if (response.success) {
                            let data = response.data;
                            Swal.fire({
                                title: data.subject,
                                html: `
                            <p><strong>Message:</strong></p>
                            <p>${data.message}</p>
                            <p><strong>Status:</strong> ${data.status == 1 ? 'Read' : 'Unread'}</p>
                            <p><strong>Time:</strong> ${data.created_at}</p>
                        `,
                                width: 600,
                                showCloseButton: true,
                            });

                            // Optionally mark as read automatically
                            if (data.status == 0) {
                                markRead(id);
                            }
                        }
                    });
                }

            });
        })(jQuery);
    </script>
@endpush
