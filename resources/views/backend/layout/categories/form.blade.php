@extends('backend.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Category</h4>
                <a href="{{ route('backend.feature.category.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5>{{ @$category ? 'Edit Category' : 'Create Category' }}</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data"
                        action="{{ @$category ? route('backend.feature.category.update', @$category->id) : route('backend.feature.category.store') }}">
                        @csrf
                        @if (@$category)
                            @method('PATCH')
                        @endif

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', @$category->title) }}" placeholder="Enter title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                value="{{ old('slug', @$category->slug) }}" placeholder="Enter slug">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        {{-- <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @if (@$category && $category->image)
                                <img src="{{ asset($category->image) }}" width="80" class="mt-2">
                            @endif
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">Select Status</option>
                                @foreach ($statuses as $key => $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', @$category->status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($key) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                {{ @$category ? 'Update' : 'Submit' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
