
<?php $__env->startSection('content'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        (function($) {
            $(function() {

                let table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "<?php echo e(route('backend.feature.contacts.index')); ?>",
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
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                // Mark as read
                window.markRead = function(id) {

                    let url = "<?php echo e(route('backend.feature.contacts.markRead', ':id')); ?>";
                    url = url.replace(':id', id);

                    $.post(url, {
                        _token: "<?php echo e(csrf_token()); ?>"
                    }, function(response) {
                        if (response.success) {
                            table.ajax.reload();
                        }
                    });
                }

                // View Contact
                window.viewContact = function(id) {
                    let url = "<?php echo e(route('backend.feature.contacts.view', ':id')); ?>";
                    url = url.replace(':id', id);

                    $.get(url, function(response) {
                        if (response.success) {
                            let data = response.data;

                            // Format created_at
                            let createdAt = new Date(data.created_at);
                            let formattedTime = createdAt
                                .toLocaleString(); // e.g., "3/1/2026, 10:30:15 PM"

                            Swal.fire({
                                title: data.subject,
                                html: `
                    <p><strong>Message:</strong></p>
                    <p>${data.message}</p>
                    <p><strong>Status:</strong> ${data.status == 1 ? 'Read' : 'Unread'}</p>
                    <p><strong>Time:</strong> ${formattedTime}</p>
                `,
                                width: 600,
                                showCloseButton: true,
                            });

                            if (data.status == 0) {
                                markRead(id);
                            }
                        }
                    });
                }

            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/contacts/index.blade.php ENDPATH**/ ?>