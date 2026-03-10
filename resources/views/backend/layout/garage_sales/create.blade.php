@extends('backend.master')
@section('title', 'Create Garage Sale')

@push('styles-bottom')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-label { font-weight: 600; color: #344767; }
        .item-card-creation { 
            position: relative; 
            padding: 1.5rem; 
            border: 1px solid #e9ecef; 
            border-radius: 1rem; 
            background-color: #f8fbff;
            transition: all 0.2s ease;
        }
        .item-card-creation:hover {
            border-color: #405189;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .btn-remove-item {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #fee2e2;
            color: #ef4444;
            transition: all 0.2s;
        }
        .btn-remove-item:hover {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }
        .btn-dashed-add {
            border: 2px dashed #e2e8f0;
            background: #fcfdff;
            color: #405189;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-dashed-add:hover {
            border-color: #405189;
            background: #f8fbff;
            color: #405189;
        }
        /* Image Preview Styles */
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .preview-item .remove-preview {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(255, 255, 255, 0.9);
            color: #ef4444;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Host a Garage Sale</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.garage.index') }}">Garage Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <form action="{{ route('backend.garage.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" name="event_title" class="form-control" placeholder="e.g. Big Spring Garage Cleanup" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Owner <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select select2" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        @php 
                                            $isMe = $user->id == auth()->id();
                                        @endphp
                                        <option value="{{ $user->id }}" {{ $isMe ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $isMe ? '(Me - Administrator)' : '' }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-map-pin-line"></i></span>
                                    <input type="text" name="pickup_location" class="form-control" placeholder="City, State, ZIP" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Tell visitors what to expect..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC INVENTORY ITEMS -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Inventory Management</h5>
                    </div>
                    <div class="card-body">
                        <div id="itemsContainer">
                            <div class="item-card-creation mb-4">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                        <input type="text" name="items[0][title]" class="form-control border-light shadow-none" placeholder="What are you selling?" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Asking Price ($) <span class="text-danger">*</span></label>
                                        <div class="input-group border-light shadow-none">
                                            <span class="input-group-text bg-white border-end-0 text-muted">$</span>
                                            <input type="number" step="0.01" name="items[0][price]" class="form-control border-start-0 ps-0" placeholder="0.00" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small">Item Description</label>
                                        <textarea name="items[0][description]" class="form-control form-control-sm border-light shadow-none" rows="2" placeholder="Describe the item condition, size, etc."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-muted"><i class="ri-image-add-line me-1"></i> Add Photos (Max 5)</label>
                                        <input type="file" name="items[0][images][]" class="form-control form-control-sm border-light shadow-none image-preview-input" multiple accept="image/*" data-target="#preview_0">
                                        <div id="preview_0" class="preview-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <button type="button" id="addItem" class="btn btn-dashed-add w-100 py-3 rounded-3">
                                <i class="ri-add-circle-fill me-2 fs-18 align-middle"></i> Add Another Item to Inventory
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Scheduling</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Main Event Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Sale Starts At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sale_start_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4 text-center">
                        <p class="text-muted small mb-4">You are creating this event as an administrator. It will be marked as "Active" immediately.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-primary">
                                <i class="ri-rocket-line me-1"></i> Launch Event
                            </button>
                            <a href="{{ route('backend.garage.index') }}" class="btn btn-soft-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts-bottom')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            let itemIndex = 1;
            $('#addItem').on('click', function() {
                const html = `
                    <div class="item-card-creation mb-4 animate__animated animate__fadeInUp" id="item_${itemIndex}">
                        <button type="button" class="btn-remove-item position-absolute top-0 end-0 mt-n2 me-n2 shadow-sm" onclick="removeItemRow(${itemIndex})" title="Remove Item">
                            <i class="ri-close-line fs-18"></i>
                        </button>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="items[${itemIndex}][title]" class="form-control border-light shadow-none" placeholder="What are you selling?" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Asking Price ($) <span class="text-danger">*</span></label>
                                <div class="input-group border-light shadow-none">
                                    <span class="input-group-text bg-white border-end-0 text-muted">$</span>
                                    <input type="number" step="0.01" name="items[${itemIndex}][price]" class="form-control border-start-0 ps-0" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Item Description</label>
                                <textarea name="items[${itemIndex}][description]" class="form-control form-control-sm border-light shadow-none" rows="2" placeholder="Describe the item condition, size, etc."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted"><i class="ri-image-add-line me-1"></i> Add Photos (Max 5)</label>
                                <input type="file" name="items[${itemIndex}][images][]" class="form-control form-control-sm border-light shadow-none image-preview-input" multiple accept="image/*" data-target="#preview_${itemIndex}">
                                <div id="preview_${itemIndex}" class="preview-container"></div>
                            </div>
                        </div>
                    </div>
                `;
                $('#itemsContainer').append(html);
                itemIndex++;
            });

            window.removeItemRow = function(index) {
                $(`#item_${index}`).fadeOut(300, function() {
                    $(this).remove();
                });
            };

            // Image Preview Functionality
            $(document).on('change', '.image-preview-input', function() {
                const input = this;
                const target = $(input).data('target');
                const $container = $(target);
                $container.empty();

                if (input.files && input.files.length > 0) {
                    Array.from(input.files).forEach((file, index) => {
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const html = `
                                    <div class="preview-item animate__animated animate__zoomIn">
                                        <img src="${e.target.result}" alt="Preview">
                                        <div class="remove-preview" onclick="clearSpecificInput(this, ${index})">
                                            <i class="ri-close-line"></i>
                                        </div>
                                    </div>
                                `;
                                $container.append(html);
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                }
            });

            window.clearSpecificInput = function(btn, index) {
                // For simplicity, we clear the entire input if they want to 'remove' one, 
                // as browser file inputs are tricky to edit per-file.
                // Alternatively, just warn or clear all.
                const $container = $(btn).closest('.preview-container');
                const inputId = $container.attr('id').replace('preview_', 'items_'); // Not exactly but we look for sibling input
                $container.prev('.image-preview-input').val('');
                $container.empty();
                toastr.info('Image selection cleared. Please re-select if needed.');
            };
        });
    </script>
@endpush
