<?php $__env->startPush('style-bottom'); ?>
<style>
    .profile-wid-img {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    .profile-setting-img {
        height: 250px;
    }
    .profile-user .user-profile-image {
        border: 4px solid var(--vz-card-bg);
        background-color: var(--vz-card-bg);
    }
    .profile-photo-edit label {
        cursor: pointer;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--vz-primary);
        font-weight: 600;
    }
    .nav-tabs-custom .nav-link.active::after {
        background-color: var(--vz-primary);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Profile Header -->
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img overflow-hidden">
            <img src="<?php echo e($profile->banner ? asset($profile->banner) : asset('assets/images/profile-bg.jpg')); ?>"
                class="profile-wid-img" alt="Profile Banner">
            <div class="overlay-content p-3 h-100 d-flex align-items-start justify-content-end">
                <div class="profile-photo-edit" style="z-index: 10;">
                    <input id="profile-foreground-img-file-input" type="file" class="profile-foreground-img-file-input d-none">
                    <label for="profile-foreground-img-file-input" class="btn btn-light btn-sm shadow-sm opacity-75-hover">
                        <i class="ri-image-edit-line align-bottom me-1"></i> Edit Cover
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary Sidebar -->
        <div class="col-xxl-3">
            <div class="card mt-n4 border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                            <img src="<?php echo e($profile->avatar ? asset($profile->avatar) : asset('assets/images/users/avatar-1.jpg')); ?>"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow-sm" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute bottom-0 end-0">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input avatar-input d-none">
                                <label for="profile-img-file-input" class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-white text-primary shadow-sm">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-18 mb-1 fw-bold text-dark"><?php echo e($user->name); ?></h5>
                        <p class="text-muted mb-4 fs-13"><i class="ri-mail-line me-1 align-bottom"></i> <?php echo e($user->email); ?></p>
                        
                        <div class="border-top border-top-dashed pt-4">
                            <div class="row text-center">
                                <div class="col-6 border-end border-end-dashed">
                                    <h6 class="mb-1 fw-bold">Role</h6>
                                    <p class="text-muted mb-0 fs-12">Administrator</p>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-1 fw-bold">Joined</h6>
                                    <p class="text-muted mb-0 fs-12"><?php echo e($user->created_at->format('M Y')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3 fs-12">Account Information</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center mb-2">
                            <i class="ri-phone-line me-2 text-muted"></i>
                            <span class="fs-13"><?php echo e($profile->phone ?? 'Not provided'); ?></span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="ri-map-pin-2-line me-2 text-muted"></i>
                            <span class="fs-13"><?php echo e($profile->address ?? 'No address'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Settings Main Panel -->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n4 border-0 shadow-lg overflow-hidden">
                <div class="card-header border-bottom-0 p-0 bg-light-subtle">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0 mx-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active px-4 py-3" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="ri-user-line align-bottom me-1"></i> Public Profile Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-4 py-3" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="ri-lock-password-line align-bottom me-1"></i> Password & Security
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content text-muted">
                        <!-- Personal Details Tab -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <div class="mb-4 text-dark">
                                <h6 class="fw-bold fs-15 mb-1">Edit Profile Infomation</h6>
                                <p class="text-muted fs-13">Update your core profile details visible to the system.</p>
                            </div>
                            <form action="<?php echo e(route('backend.settings.profile.update')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label fw-semibold">Display Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ri-user-follow-line text-muted"></i></span>
                                                <input type="text" name="name" class="form-control border-start-0" 
                                                    placeholder="Enter full name" value='<?php echo e(old('name',$user->name)); ?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label fw-semibold">Contact Phone</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ri-phone-fill text-muted"></i></span>
                                                <input type="text" name="phone" class="form-control border-start-0" 
                                                    placeholder="+8801..." value='<?php echo e(old('phone',$profile->phone)); ?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label fw-semibold">Residential Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="ri-map-pin-line text-muted"></i></span>
                                                <textarea name="address" class="form-control border-start-0" rows="3"
                                                    placeholder="Enter your detailed office/home address"><?php echo e(old('address',$profile->address)); ?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="hstack gap-2 justify-content-end mt-2">
                                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light shadow-sm">
                                                <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <div class="mb-4 text-dark">
                                <h6 class="fw-bold fs-15 mb-1">Update Password</h6>
                                <p class="text-muted fs-13">Ensuring you have a strong password is key to account security.</p>
                            </div>
                            <form action="<?php echo e(route('auth.reset.post')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <label class="form-label fw-semibold">Current Password</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="position-relative auth-pass-inputgroup text-dark">
                                            <input type="password" class="form-control password-input" name="curr_password"
                                                placeholder="Verify your identity" id="password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label fw-semibold">New Password</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="position-relative auth-pass-inputgroup text-dark">
                                            <input type="password" class="form-control password-input" name="password" 
                                                placeholder="Use letters, numbers & symbols" id="new-password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" onclick="togglePasswordVisibility('new-password-input', this)"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <label class="form-label fw-semibold">Confirm Password</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="position-relative auth-pass-inputgroup text-dark">
                                            <input type="password" class="form-control password-input"
                                                name="password_confirmation" placeholder="Repeat new password" id="confirm-password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" onclick="togglePasswordVisibility('confirm-password-input', this)"><i class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary btn-label waves-effect waves-light shadow-sm">
                                            <i class="ri-lock-2-line label-icon align-middle fs-16 me-2"></i> Update Security
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
            } else {
                input.type = "password";
                icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
            }
        }

        $(document).ready(function () {
            $('#password-addon').on('click', function() {
                togglePasswordVisibility('password-input', this);
            });
            
            // Avatar Upload
            $('.avatar-input').on('change', function () {
                const formData = new FormData();
                formData.append('avatar', $(this)[0].files[0]);
                formData.append('_token', "<?php echo e(csrf_token()); ?>");
                formData.append('profile_id', "<?php echo e($profile->id); ?>")

                $.ajax({
                    url: "<?php echo e(route('backend.settings.profile.avatar.upload')); ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('.user-profile-image').attr('src', response.url);
                            $('.header-profile-user').attr('src', response.url);
                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                icon: "success",
                                title: response.message || "Avatar updated",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire({ toast: true, position: "top-end", icon: "error", title: response.message || "Failed to upload" });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ toast: true, position: "top-end", icon: "error", title: "Server Error" });
                    }
                });
            });

            // Banner Upload
            $('.profile-foreground-img-file-input').on('change', function () {
                const formData = new FormData();
                formData.append('banner', $(this)[0].files[0]);
                formData.append('_token', "<?php echo e(csrf_token()); ?>");
                formData.append('profile_id', "<?php echo e($profile->id); ?>")

                $.ajax({
                    url: "<?php echo e(route('backend.settings.profile.banner.upload')); ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('.profile-wid-img').attr('src', response.url);
                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                icon: "success",
                                title: response.message || "Cover photo updated",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire({ toast: true, position: "top-end", icon: "error", title: response.message || "Failed to update cover" });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ toast: true, position: "top-end", icon: "error", title: "Server Error" });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/layout/settings/profile.blade.php ENDPATH**/ ?>