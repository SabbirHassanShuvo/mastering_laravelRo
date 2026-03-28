@extends('backend.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">System Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">System Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('backend.settings.system.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Branding Section -->
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-palette-line align-middle me-1 text-primary"></i> Branding & Logos</h4>
                        <div class="flex-shrink-0">
                            <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                                <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Save Settings
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Logo -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Company Logo</label>
                                <input type="file" name="logo"
                                    class="dropify @error('logo') is-invalid @enderror" data-height="120"
                                    @if(!empty($settings->logo)) data-default-file="{{ asset($settings->logo) }}" @endif>
                                @error('logo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <p class="text-muted mt-2 fs-12 text-center">Transparent background recommended (PNG/SVG)</p>
                            </div>

                            <!-- Mini Logo -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mini Logo / Sidebar Logo</label>
                                <input type="file" name="mini_logo"
                                    class="dropify @error('mini_logo') is-invalid @enderror" data-height="120"
                                    @if(!empty($settings->mini_logo)) data-default-file="{{ asset($settings->mini_logo) }}" @endif>
                                @error('mini_logo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <p class="text-muted mt-2 fs-12 text-center">Small icon for collapsed sidebar</p>
                            </div>

                            <!-- Icon -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Favicon / Browser Icon</label>
                                <input type="file" name="icon"
                                    class="dropify @error('icon') is-invalid @enderror" data-height="120"
                                    @if(!empty($settings->icon)) data-default-file="{{ asset($settings->icon) }}" @endif>
                                @error('icon')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <p class="text-muted mt-2 fs-12 text-center">Standard 32x32 or 64x64 icon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-settings-4-line align-middle me-1 text-primary"></i> General Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Website Title</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-global-line"></i></span>
                                <input type="text" name="site_title"
                                    class="form-control @error('site_title') is-invalid @enderror"
                                    placeholder="e.g. SwapApp - Professional Services"
                                    value="{{ old('site_title', $settings->site_title ?? '') }}">
                            </div>
                            @error('site_title')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">App Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-app-store-line"></i></span>
                                <input type="text" name="app_name"
                                    class="form-control @error('app_name') is-invalid @enderror"
                                    placeholder="e.g. SwapApp"
                                    value="{{ old('app_name', $settings->app_name ?? 'SwapApp') }}">
                            </div>
                            @error('app_name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Admin Dashboard Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-dashboard-line"></i></span>
                                <input type="text" name="admin_name"
                                    class="form-control @error('admin_name') is-invalid @enderror"
                                    placeholder="e.g. SwapApp Admin"
                                    value="{{ old('admin_name', $settings->admin_name ?? 'SwapApp') }}">
                            </div>
                            @error('admin_name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Footer -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-contacts-book-2-line align-middle me-1 text-primary"></i> Contact & Footer</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Copyright Text</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-copyright-line"></i></span>
                                <input type="text" name="copyright"
                                    class="form-control @error('copyright') is-invalid @enderror"
                                    placeholder="© 2025 SwapApp. All rights reserved."
                                    value="{{ old('copyright', $settings->copyright ?? '') }}">
                            </div>
                            @error('copyright')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="text" name="contact"
                                        class="form-control @error('contact') is-invalid @enderror"
                                        placeholder="+1234567890"
                                        value="{{ old('contact', $settings->contact ?? '') }}">
                                </div>
                                @error('contact')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Contact Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="contact@swapapp.com"
                                        value="{{ old('email', $settings->email ?? '') }}">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">About Section</label>
                            <textarea id="about-editor" name="about" rows="3" class="form-control"
                                placeholder="Brief summary for footer/about page...">{{ old('about', $settings->about ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service Fees -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-money-dollar-circle-line align-middle me-1 text-primary"></i> Service Fees</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Garage Sale Creation Fee ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-coins-line"></i></span>
                                    <input type="number" step="0.01" name="garage_fee"
                                        class="form-control @error('garage_fee') is-invalid @enderror"
                                        placeholder="2.99"
                                        value="{{ old('garage_fee', $settings->garage_fee ?? '2.99') }}">
                                </div>
                                @error('garage_fee')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Product Spotlight Boost Fee ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-flashlight-line"></i></span>
                                    <input type="number" step="0.01" name="spotlight_fee"
                                        class="form-control @error('spotlight_fee') is-invalid @enderror"
                                        placeholder="2.99"
                                        value="{{ old('spotlight_fee', $settings->spotlight_fee ?? '2.99') }}">
                                </div>
                                @error('spotlight_fee')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-2 mb-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                        <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style-bottom')
    <style>
        .dropify-wrapper .dropify-message p {
            line-height: 1.5;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }
        .card-title {
            font-weight: 600;
        }
        .input-group-text {
            background-color: #f3f6f9;
            color: #495057;
        }
    </style>
@endpush

@push('scripts-top')
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if(document.querySelector('#about-editor')){
            ClassicEditor
                .create(document.querySelector('#about-editor'))
                .then(editor \=\> {
                    console.log('CKEditor initialized');
                })
                .catch(error \=\> {
                    console.error('CKEditor error:', error);
                });
        }
    });
</script>
@endpush

