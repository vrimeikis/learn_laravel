<?php

declare(strict_types =1);

use App\Enum\ArticleTypeEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArticleTypeOnArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws ReflectionException
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->enum('article_type', ArticleTypeEnum::enum())
                ->default(ArticleTypeEnum::draft()->getId());
        });

        DB::table('articles')->update([
            'article_type' => ArticleTypeEnum::published()->getId(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['article_type']);
        });
    }
}
