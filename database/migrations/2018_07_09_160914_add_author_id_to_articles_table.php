<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorIdToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn ('articles', 'author_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->integer('author_id')->unsigned()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn ('articles', 'author_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('author_id');
            });
        }
    }
}
