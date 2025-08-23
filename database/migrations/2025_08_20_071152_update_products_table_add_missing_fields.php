<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add missing fields that are in the Product model
            $table->string('brand')->nullable()->after('category_id');
            $table->string('sku')->unique()->after('brand');
            $table->string('image_url')->nullable()->after('sku');
            $table->boolean('is_active')->default(true)->after('image_url');
            $table->decimal('weight', 8, 2)->nullable()->after('is_active');
            $table->string('dimensions')->nullable()->after('weight');
            
            // Rename image to image_url if needed
            // The original migration has 'image' but our model expects 'image_url'
            if (Schema::hasColumn('products', 'image')) {
                $table->renameColumn('image', 'image_old');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'sku', 'image_url', 'is_active', 'weight', 'dimensions']);
            
            if (Schema::hasColumn('products', 'image_old')) {
                $table->renameColumn('image_old', 'image');
            }
        });
    }
};
