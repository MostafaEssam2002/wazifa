<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('user_id'); // إضافة عمود category_id
            $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('set null'); // إضافة علاقة مع جدول post_categories
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // حذف العلاقة
            $table->dropColumn('category_id'); // حذف العمود
        });
    }
}
