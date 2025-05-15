<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Surfsidemedia\Shoppingcart\Facades\Cart;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            if (Schema::hasColumn('orders', 'shipping_city')) {
                $table->dropColumn('shipping_city');
            }
            if (Schema::hasColumn('orders', 'shipping_state')) {
                $table->dropColumn('shipping_state');
            }
            if (Schema::hasColumn('orders', 'shipping_zipcode')) {
                $table->dropColumn('shipping_zipcode');
            }
            if (Schema::hasColumn('orders', 'shipping_phone')) {
                $table->dropColumn('shipping_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_zipcode');
            $table->string('shipping_phone');
        });
    }
}; 