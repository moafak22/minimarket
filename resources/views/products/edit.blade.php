@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <h1 class="display-6 fw-bold text-primary">
                    <i class="fas fa-edit me-2"></i> Edit Product
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.show', $product) }}" 
                       class="btn btn-info">
                        <i class="fas fa-eye me-1"></i> View Product
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Products
                    </a>
                </div>
            </div>

            <!-- Product Edit Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i> Edit Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Current Product Info -->
                    <div class="alert alert-light border mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i> Current Product Information
                        </h6>
                        <div class="row g-3 small">
                            <div class="col-md-6">
                                <strong>SKU:</strong> {{ $product->sku ?? 'Not set' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong> 
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   required
                                   class="form-control @error('name') is-invalid @enderror">
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
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
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
                                       value="{{ old('price', $product->price) }}"
                                       required
                                       class="form-control @error('price') is-invalid @enderror">
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                   required
                                   class="form-control @error('stock_quantity') is-invalid @enderror">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($product->stock_quantity < 10)
                                <div class="alert alert-warning mt-2" role="alert">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <small>Low stock warning: Consider restocking soon</small>
                                </div>
                            @endif
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
                                    <option value="{{ $category->id }}" 
                                            {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current and New Product Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Product Image
                            </label>
                            
                            <!-- Current Image Display -->
                            @if($product->image)
                                <div class="mb-3">
                                    <p class="form-text mb-2">Current Image:</p>
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded border" 
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <span class="position-absolute top-0 end-0 badge bg-success rounded-pill">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload New Image -->
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Supported formats: PNG, JPG, GIF. Maximum size: 10MB.
                                @if($product->image)
                                    Leave empty to keep current image.
                                @endif
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Remove Current Image Option -->
                            @if($product->image)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                    <label class="form-check-label" for="remove_image">
                                        <i class="fas fa-trash text-danger me-1"></i>
                                        Remove current image
                                    </label>
                                </div>
                            @endif
                        </div>

                        <!-- SKU (Stock Keeping Unit) -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">
                                SKU (Stock Keeping Unit)
                            </label>
                            <input type="text" 
                                   id="sku" 
                                   name="sku" 
                                   value="{{ old('sku', $product->sku) }}"
                                   class="form-control @error('sku') is-invalid @enderror">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-toggle-on text-success me-1"></i>
                                    Product is active and available for sale
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top">
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                                <button type="submit" 
                                        class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Product
                                </button>
                            </div>
                            
                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="btn btn-danger delete-product-btn"
                                        data-product-name="{{ $product->name }}">
                                    <i class="fas fa-trash me-1"></i> Delete Product
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Warning -->
            <div class="alert alert-warning mt-4" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i> Important Notice
                </h6>
                <p class="mb-0 small">
                    Make sure to review all changes before updating. 
                    Changes to price and stock quantity will affect current orders and inventory tracking.
                </p>
            </div>
    </div>
</div>

@push('scripts')
<script>
// Image preview functionality with validation
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
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
                    let preview = document.getElementById('new-image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'new-image-preview';
                        preview.className = 'mt-3';
                        document.querySelector('[for="image"]').closest('div').appendChild(preview);
                    }
                    
                    preview.innerHTML = `
                        <div class="card">
                            <div class="card-body p-2">
                                <p class="small mb-2 text-muted">New Image Preview:</p>
                                <div class="position-relative d-inline-block">
                                    <img src="${event.target.result}" alt="New Preview" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                                    <button type="button" onclick="this.closest('#new-image-preview').remove(); document.getElementById('image').value = '';" 
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
    }

    // Client-side validation helpers
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function(e) {
            const value = parseFloat(e.target.value);
            if (value <= 0) {
                e.target.setCustomValidity('Price must be greater than $0.00');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }

    const stockInput = document.getElementById('stock_quantity');
    if (stockInput) {
        stockInput.addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            if (value < 0) {
                e.target.setCustomValidity('Stock quantity cannot be negative');
            } else {
                e.target.setCustomValidity('');
            }
        });
    }
});
</script>
@endpush
@endsection
