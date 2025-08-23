<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with search, filter, and sort functionality.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->byCategory($request->category_id);
        }

        // Filter by brand
        if ($request->has('brand') && !empty($request->brand)) {
            $query->byBrand($request->brand);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $maxPrice = $request->has('max_price') ? $request->max_price : null;
            $query->byPriceRange($request->min_price, $maxPrice);
        }

        // Filter by active status
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // Filter by stock availability
        if ($request->has('in_stock_only') && $request->in_stock_only) {
            $query->inStock();
        }

        // Sorting functionality
        $sort = $request->get('sort', 'created_desc');
        
        // Parse sort parameter (format: field_direction)
        if ($sort) {
            // Handle multi-part field names like stock_quantity
            if (strpos($sort, '_asc') !== false) {
                $sortBy = str_replace('_asc', '', $sort);
                $sortDirection = 'asc';
            } elseif (strpos($sort, '_desc') !== false) {
                $sortBy = str_replace('_desc', '', $sort);
                $sortDirection = 'desc';
            } else {
                $sortBy = 'created_at';
                $sortDirection = 'desc';
            }
        } else {
            $sortBy = 'created_at';
            $sortDirection = 'desc';
        }

        switch ($sortBy) {
            case 'price':
                $query->orderByPrice($sortDirection);
                break;
            case 'name':
                $query->orderBy('name', $sortDirection);
                break;
            case 'stock_quantity':
                $query->orderBy('stock_quantity', $sortDirection);
                break;
            case 'created':
                $query->orderBy('created_at', $sortDirection);
                break;
            case 'popular':
                $query->popular();
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);
        
        // Append query parameters to pagination links
        $products->appends($request->query());

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $products,
                'filters' => [
                    'search' => $request->search,
                    'category_id' => $request->category_id,
                    'brand' => $request->brand,
                    'min_price' => $request->min_price,
                    'max_price' => $request->max_price,
                    'sort_by' => $sortBy,
                    'sort_direction' => $sortDirection,
                ]
            ]);
        }

        // Get all categories for the dropdown filter
        $categories = Category::active()->orderBy('name')->get();

        // Return view for web requests
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage with validation.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validate the request with custom messages
        $validated = $request->validate(Product::validationRules(), Product::validationMessages());

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $this->handleImageUpload($request->file('image'));
            }

            // Create the product
            $product = Product::create($validated);

            // Return JSON for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully.',
                    'data' => $product
                ], 201);
            }

            // Redirect for web requests
            return redirect()->route('products.show', $product)
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource details.
     */
    public function show(Product $product): View|JsonResponse
    {
        // Return JSON for API requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }

        // Return view for web requests
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage with validation.
     */
    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        // Validate the request with update rules and custom messages
        $rules = Product::updateValidationRules($product->id);
        $rules['remove_image'] = 'sometimes|boolean';
        $messages = Product::validationMessages();
        $messages['remove_image.boolean'] = 'Remove image field must be true or false.';
        $validated = $request->validate($rules, $messages);

        try {
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                $this->deleteImage($product->image);
                $validated['image'] = null;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    $this->deleteImage($product->image);
                }
                $validated['image'] = $this->handleImageUpload($request->file('image'));
            }

            // Remove non-model fields from validated data
            unset($validated['remove_image']);

            // Update the product
            $product->update($validated);

            // Return JSON for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'data' => $product->fresh()
                ]);
            }

            // Redirect for web requests
            return redirect()->route('products.show', $product)
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse|JsonResponse
    {
        try {
            $productName = $product->name;
            
            // Delete associated image
            if ($product->image) {
                $this->deleteImage($product->image);
            }
            
            $product->delete();

            // Return JSON for API requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully.'
                ]);
            }

            // Redirect for web requests
            return redirect()->route('products.index')
                ->with('success', "Product '{$productName}' deleted successfully.");
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete product.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Handle image upload and return the stored file path.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @return string
     */
    private function handleImageUpload($image): string
    {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        
        // Store in storage/app/public/products
        $path = $image->storeAs('products', $filename, 'public');
        
        return $path;
    }

    /**
     * Delete image from storage.
     *
     * @param string|null $imagePath
     * @return void
     */
    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
