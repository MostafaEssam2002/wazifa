<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
{
    Schema::table('likes', function (Blueprint $table) {
        if (Schema::hasColumn('likes', 'posts_id')) {
            $table->renameColumn('posts_id', 'post_id');
        }
    });
}

public function down()
{
    Schema::table('likes', function (Blueprint $table) {
        if (Schema::hasColumn('likes', 'post_id')) {
            $table->renameColumn('post_id', 'posts_id');
        }
    });
}
};
