@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6 fw-bold text-primary">
                    <i class="fas fa-plus-circle me-2"></i> Create New Product
                </h1>
                <a href="{{ route('products.index') }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Products
                </a>
            </div>

            <!-- Product Creation Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i> Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter product name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter product description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label for="price" class="form-label">
                                Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       step="0.01"
                                       min="0"
                                       value="{{ old('price') }}"
                                       required
                                       class="form-control @error('price') is-invalid @enderror"
                                       placeholder="0.00">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">
                                Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   id="stock_quantity" 
                                   name="stock_quantity" 
                                   min="0"
                                   value="{{ old('stock_quantity', 0) }}"
                                   required
                                   class="form-control @error('stock_quantity') is-invalid @enderror"
                                   placeholder="Enter stock quantity">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    required
                                    class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Product Image
                            </label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Supported formats: PNG, JPG, GIF. Maximum size: 10MB
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU (Stock Keeping Unit) -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">
                                SKU (Stock Keeping Unit)
                            </label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku') }}"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   placeholder="Enter SKU or leave blank to auto-generate">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave blank to auto-generate a unique SKU
                            </div>
                        </div>

                        <!-- Is Active -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    Product is active and available for sale
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                            <a href="{{ route('products.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb me-2"></i> Tips for Creating Products
                </h6>
                <ul class="mb-0 small">
                    <li>Use high-quality images for better product presentation</li>
                    <li>Include detailed descriptions to help customers make informed decisions</li>
                    <li>Set competitive prices based on market research</li>
                    <li>Keep stock quantities updated to avoid overselling</li>
                    <li>Choose appropriate categories to help customers find your products</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image preview functionality
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (10MB = 10240KB)
            if (file.size > 10240 * 1024) {
                alert('Image size cannot exceed 10MB. Please choose a smaller file.');
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, or GIF).');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                // Create preview if doesn't exist
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.className = 'mt-3';
                    document.querySelector('[for="image"]').closest('div').appendChild(preview);
                }
                
                preview.innerHTML = `
                    <div class="card">
                        <div class="card-body p-2">
                            <p class="small mb-2 text-muted">Image Preview:</p>
                            <div class="position-relative d-inline-block">
                                <img src="${event.target.result}" alt="Preview" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                                <button type="button" onclick="this.closest('#image-preview').remove(); document.getElementById('image').value = '';" 
                                        class="btn btn-danger btn-sm position-absolute" style="top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; padding: 0; line-height: 1;">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Client-side validation helpers
    document.getElementById('price').addEventListener('input', function(e) {
        const value = parseFloat(e.target.value);
        if (value <= 0) {
            e.target.setCustomValidity('Price must be greater than $0.00');
        } else {
            e.target.setCustomValidity('');
        }
    });

    document.getElementById('stock_quantity').addEventListener('input', function(e) {
        const value = parseInt(e.target.value);
        if (value < 0) {
            e.target.setCustomValidity('Stock quantity cannot be negative');
        } else {
            e.target.setCustomValidity('');
        }
    });
});
</script>
@endpush
@endsection
