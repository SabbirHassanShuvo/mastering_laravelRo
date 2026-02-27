<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="tasksList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">All FAQs</h5>
                        <div class="flex-shrink-0">
                            <a class="btn btn-danger add-btn" href="<?php echo e(route('backend.feature.faq.create')); ?>">
                                <i class="ri-add-line align-bottom me-1"></i> Create FAQ
                            </a>

                            
                        </div>
                    </div>
                </div>

                <!--end card-body-->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">FAQ List</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="search-box position-relative">
                                    <input type="text" id="customSearch" class="form-control ps-5"
                                        placeholder="Search FAQ by question or answer...">
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
                        <div class="table-responsive table-card mb-4">
                            <table class="table align-middle table-nowrap table-striped mb-0 data-table">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="wd-10p border-bottom-0">ID</th>
                                        <th class="wd-30p border-bottom-0">Question</th>
                                        <th class="wd-30p border-bottom-0">Answer</th>
                                        <th class="wd-10p border-bottom-0">Priority</th>
                                        <th class="wd-10p border-bottom-0">Status</th>
                                        <th class="wd-10p border-bottom-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-top'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        (function($) {
            $(function() {

                let table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: {
                        details: true
                    },
                    dom: 'lrtip', // hide default search

                    ajax: "<?php echo e(route('backend.feature.faq.index')); ?>",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'question',
                            name: 'question'
                        },
                        {
                            data: 'answer',
                            name: 'answer'
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

                // Custom Search
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Refresh Button
                $('#refreshTable').on('click', function() {
                    $('#customSearch').val('');
                    table.search('').draw();
                });

            });
        })(jQuery);

        $(document).on('shown.bs.collapse shown.bs.tab', function() {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

        function statusFaq(id) {
            let url = "<?php echo e(route('backend.feature.faq.status', ':id')); ?>";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                data: {
                    id: id,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    console.log(response);
                    // Reloade DataTable
                    $('.datatable').DataTable().ajax.reload();
                    if (response.success) {
                        $('.data-table').DataTable().ajax.reload();
                        Swal.fire({
                            toast: true,
                            position: "top-end", // top-end, top-start, bottom-end, bottom-start
                            icon: "success",
                            title: response.message || "Faq Deleted successfully",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "error",
                            title: response.message || "Something went wrong",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function(error) {
                    // location.reload();
                }
            });
        }

        function editFaq(id) {
            let url = "<?php echo e(route('backend.feature.faq.edit', ':id')); ?>";
            url = url.replace(':id', id);

            window.location.href = url;
        }

        function deleteData(url) {

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this FAQ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function(response) {

                            if (response.success) {

                                $('.data-table').DataTable().ajax.reload();

                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "success",
                                    title: response.message || "FAQ deleted successfully",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });

                            } else {

                                Swal.fire({
                                    toast: true,
                                    position: "top-end",
                                    icon: "error",
                                    title: response.message || "Something went wrong",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });

                            }
                        }
                    });

                }

                // If cancel clicked → automatically closes, no extra code needed

            });

        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/faqs/index.blade.php ENDPATH**/ ?>