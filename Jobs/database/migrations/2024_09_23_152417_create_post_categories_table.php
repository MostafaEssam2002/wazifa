<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line
class CreatePostCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Category name
            $table->timestamps();
        });
        DB::table('post_categories')->insert([['name' => 'Technology'],['name' => 'Health'],['name' => 'Education'],['name' => 'Finance'],['name' => 'Travel'],]);
    }
    public function down()
    {
        Schema::dropIfExists('post_categories');
    }
}