<?php

declare(strict_types =1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceOnAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasColumn('authors', 'reference_author_id')) {
            Schema::table('authors', function(Blueprint $table) {
                $table->integer('reference_author_id')
                    ->nullable()
                    ->comment('Author id from 3trd party application');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            //
        });
    }
}
