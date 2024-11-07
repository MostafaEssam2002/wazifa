<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLikesTable extends Migration
{
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {
            // تحقق من وجود العمود لتجنب إضافته مرة أخرى
            if (!Schema::hasColumn('likes', 'post_id')) {
                $table->unsignedBigInteger('post_id')->after('id');

                // إضافة المفتاح الخارجي إذا لزم الأمر
                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            if (Schema::hasColumn('likes', 'post_id')) {
                $table->dropForeign(['post_id']);
                $table->dropColumn('post_id');
            }
        });
    }
}
