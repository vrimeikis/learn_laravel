<?php

declare(strict_types =1);

use App\Enum\AuthorLocationTypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationTypeOnAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->enum('location_type', AuthorLocationTypeEnum::enum())
                ->default(AuthorLocationTypeEnum::local()->getId());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['location_type']);
        });
    }
}
