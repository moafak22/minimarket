<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'brand',
        'sku',
        'image',
        'image_url',
        'is_active',
        'weight',
        'dimensions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the validation rules for the product.
     *
     * @return array<string, string>
     */
    public static function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:100',
            'sku' => 'required|string|max:100|unique:products,sku',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get the validation rules for updating a product.
     *
     * @param int $productId
     * @return array<string, string>
     */
    public static function updateValidationRules(int $productId): array
    {
        $rules = self::validationRules();
        $rules['sku'] = 'required|string|max:100|unique:products,sku,' . $productId;
        return $rules;
    }

    /**
     * Get custom validation messages for better user experience.
     *
     * @return array<string, string>
     */
    public static function validationMessages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a valid text.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Product price must be a valid number.',
            'price.min' => 'Product price must be greater than $0.00.',
            
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            
            'category_id.required' => 'Please select a category for this product.',
            'category_id.exists' => 'The selected category is invalid.',
            
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Image size cannot exceed 10MB.',
            
            'sku.required' => 'SKU (Stock Keeping Unit) is required.',
            'sku.unique' => 'This SKU already exists. Please use a different SKU.',
            'sku.max' => 'SKU cannot exceed 100 characters.',
            
            'description.max' => 'Description cannot exceed 1000 characters.',
            'brand.max' => 'Brand name cannot exceed 100 characters.',
        ];
    }

    /**
     * Get the formatted price attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Scope a query to search for products by name, description, or SKU.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('brand', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter products by category.
     *
     * @param Builder $query
     * @param int $categoryId
     * @return Builder
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter products by brand.
     *
     * @param Builder $query
     * @param string $brand
     * @return Builder
     */
    public function scopeByBrand(Builder $query, string $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope a query to filter products by price range.
     *
     * @param Builder $query
     * @param float $minPrice
     * @param float|null $maxPrice
     * @return Builder
     */
    public function scopeByPriceRange(Builder $query, float $minPrice, ?float $maxPrice = null): Builder
    {
        $query->where('price', '>=', $minPrice);
        
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include active products.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products that are in stock.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope a query to order products by popularity (you can customize this logic).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePopular(Builder $query): Builder
    {
        // This is a placeholder - you might want to join with sales/order data
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order products by price.
     *
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByPrice(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all unique categories that have products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUniqueCategories()
    {
        return Category::whereHas('products')->active()->orderBy('name')->get();
    }

}
