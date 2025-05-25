<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProductsConditionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using DB::statement to modify ENUM values
        DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('new', 'like_new', 'used')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverting to previous ENUM values if needed
        DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('new', 'used')");
    }
}