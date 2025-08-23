@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="fas fa-box-open me-2"></i> Products
        </h1>
        <a href="{{ route('products.create') }}" 
           class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus me-1"></i> Add New Product
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i> Search & Filter Products
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="row g-3">
                    <!-- Search Bar -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Search Products</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search products..."
                                   class="form-control">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="">Default (Newest First)</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                            <option value="stock_quantity_asc" {{ request('sort') == 'stock_quantity_asc' ? 'selected' : '' }}>Stock: Low to High</option>
                            <option value="stock_quantity_desc" {{ request('sort') == 'stock_quantity_desc' ? 'selected' : '' }}>Stock: High to Low</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mt-4">
                    <div>
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    </div>

                    <!-- View Toggle -->
                    <div class="btn-group" role="group" aria-label="View toggle">
                        <input type="radio" class="btn-check" name="view-toggle" id="view-grid" {{ request('view', 'grid') === 'grid' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="view-grid">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="text-decoration-none">
                                <i class="fas fa-th me-1"></i> Grid
                            </a>
                        </label>

                        <input type="radio" class="btn-check" name="view-toggle" id="view-list" {{ request('view') === 'list' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="view-list">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="text-decoration-none">
                                <i class="fas fa-list me-1"></i> List
                            </a>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Display -->
    @if($products->count() > 0)
        @if(request('view') === 'list')
            <!-- List View -->
            <div class="card">
                <div class="card-body p-0">
                    @foreach($products as $product)
                        <div class="border-bottom p-3 {{ $loop->last ? 'border-0' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" 
                                             style="width: 60px; height: 60px;">
                                            <small>No Image</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                    <p class="text-muted small mb-0">{{ Str::limit($product->description, 100) }}</p>
                                </div>
                                <div class="text-end">
                                    <h4 class="text-success mb-1">${{ number_format($product->price, 2) }}</h4>
                                    <small class="text-muted d-block">Stock: {{ $product->stock_quantity }}</small>
                                    <div class="mt-2">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-product-btn"
                                                    data-product-name="{{ $product->name }}">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Grid View -->
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <div class="position-relative">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" 
                                         class="card-img-top" alt="{{ $product->name }}" 
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" 
                                         style="height: 200px;">
                                        <span>No Image</span>
                                    </div>
                                @endif
                                
                                @if(!$product->is_active)
                                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">Inactive</span>
                                @endif
                                
                                @if($product->stock_quantity === 0)
                                    <span class="position-absolute top-0 end-0 badge bg-danger m-2">Out of Stock</span>
                                @elseif($product->stock_quantity < 10)
                                    <span class="position-absolute top-0 end-0 badge bg-warning m-2">Low Stock</span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                
                                <div class="mb-2">
                                    @if($product->category)
                                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Uncategorized</span>
                                    @endif
                                </div>
                                
                                @if($product->description)
                                    <p class="card-text text-muted small flex-grow-1">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="text-success mb-0">${{ number_format($product->price, 2) }}</h4>
                                    <small class="text-muted">{{ $product->stock_quantity }} in stock</small>
                                </div>
                                
                                @if($product->sku)
                                    <div class="text-muted small mb-3">SKU: {{ $product->sku }}</div>
                                @endif
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-primary flex-fill">View</a>
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-outline-danger delete-product-btn"
                                                    data-product-name="{{ $product->name }}"
                                                    title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top text-muted small">
                                    <span>Created {{ $product->created_at->format('M d') }}</span>
                                    @if($product->updated_at->ne($product->created_at))
                                        <span>Updated {{ $product->updated_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        <nav aria-label="Products pagination" class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-muted small">
                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                    of {{ $products->total() }} products
                </div>
                <div>
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </nav>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-box-open fa-4x text-muted"></i>
                </div>
                <h4 class="card-title">No products found</h4>
                <p class="card-text text-muted mb-4">
                    @if(request('search') || request('category_id') || request('sort'))
                        No products match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first product.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Product
                    </a>
                    @if(request('search') || request('category_id') || request('sort'))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when sort dropdown changes
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.querySelector('select[name="sort"]');
        const categorySelect = document.querySelector('select[name="category_id"]');
        const form = sortSelect.closest('form');
        
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        // Optional: Add search input auto-submit with delay
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if (searchInput.value.length >= 3 || searchInput.value.length === 0) {
                        form.submit();
                    }
                }, 800); // Wait 800ms after user stops typing
            });
        }
    });
</script>
@endpush
