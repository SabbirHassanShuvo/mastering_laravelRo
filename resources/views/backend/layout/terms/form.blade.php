@extends('backend.master')
@section('title', 'Dashboard | faq form')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Create Terms</h4>
                    <a href="{{ route('backend.feature.terms.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Terms</a></li>
                        <li class="breadcrumb-item active">Create Terms</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form method="post"
        action="{{ isset($term) ? route('backend.feature.terms.update', $term->id) : route('backend.feature.terms.store') }}"
        class="row">
        @csrf
        @if (isset($term))
            @method('PATCH')
        @endif

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <!-- Title -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Terms Title</label>
                                <input type="text" name="title" value="{{ old('title', $term->title ?? '') }}"
                                    class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <input type="number" name="priority" value="{{ old('priority', $term->priority ?? 1) }}"
                                    class="form-control @error('priority') is-invalid @enderror">
                                @error('priority')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="ckeditor-classic" class="@error('description') is-invalid @enderror">
                                {{ old('description', $term->description ?? '') }}
                            </textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-end mb-4 me-3">
                    <a href="{{ route('backend.feature.terms.index') }}" class="btn btn-danger w-sm">Cancel</a>

                    <button type="submit" class="btn btn-success w-sm">
                        {{ isset($term) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Visibility</h5>
                </div>

                <div class="card-body">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">

                        <option value="" disabled>Select Option</option>

                        @foreach ($status as $key => $value)
                            <option value="{{ $value }}"
                                {{ old('status', $term->status ?? '') == $value ? 'selected' : '' }}>
                                {{ $key }}
                            </option>
                        @endforeach

                    </select>

                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

    </form>
    <!-- end row -->

@endsection
@push('style-bottom')
    <style>
        .dropify-wrapper .dropify-message p {
            line-height: 2;
            /* increase spacing */
            font-size: 16px;
            /* adjust font size if needed */
            color: #555;
            /* custom text color */
        }
    </style>
@endpush

@push('scripts-top')
    <!-- ckeditor -->
    <script src="{{ asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <!-- project-create init -->
    <script src="{{ asset('') }}assets/js/pages/project-create.init.js"></script>
@endpush
