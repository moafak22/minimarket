<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Product Image -->
    <div class="aspect-square bg-gray-200 relative overflow-hidden">
        @if($product->image)
            <img src="{{ Storage::url($product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
        
        <!-- Status Badge -->
        @if(!$product->is_active)
            <div class="absolute top-2 left-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Inactive
                </span>
            </div>
        @endif

        <!-- Stock Badge -->
        @if($product->stock_quantity === 0)
            <div class="absolute top-2 right-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Out of Stock
                </span>
            </div>
        @elseif($product->stock_quantity < 10)
            <div class="absolute top-2 right-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Low Stock
                </span>
            </div>
        @endif

        <!-- Quick Action Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
            <div class="flex space-x-2">
                <a href="{{ route('products.show', $product) }}" 
                   class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                   title="View Product">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a href="{{ route('products.edit', $product) }}" 
                   class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                   title="Edit Product">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="p-4">
        <!-- Product Name -->
        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">
            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Category -->
        <div class="mb-2">
            @if($product->category)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $product->category->name }}
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Uncategorized
                </span>
            @endif
        </div>

        <!-- Description Preview -->
        @if($product->description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ Str::limit($product->description, 80) }}
            </p>
        @endif

        <!-- Price and Stock -->
        <div class="flex items-center justify-between mb-3">
            <div class="text-2xl font-bold text-green-600">
                ${{ number_format($product->price, 2) }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $product->stock_quantity }} in stock
            </div>
        </div>

        <!-- SKU -->
        @if($product->sku)
            <div class="text-xs text-gray-500 mb-3">
                SKU: {{ $product->sku }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('products.show', $product) }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                View Details
            </a>
            <a href="{{ route('products.edit', $product) }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-3 rounded text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-200 hover:bg-red-300 text-red-800 py-2 px-3 rounded text-sm font-medium transition-colors"
                        onclick="return confirm('Are you sure you want to delete this product?')"
                        title="Delete Product">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Additional Info Footer -->
        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
            <span>Created {{ $product->created_at->format('M d') }}</span>
            @if($product->updated_at->ne($product->created_at))
                <span>Updated {{ $product->updated_at->diffForHumans() }}</span>
            @endif
        </div>
    </div>
</div>

<style>
/* Line clamp utility for text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
