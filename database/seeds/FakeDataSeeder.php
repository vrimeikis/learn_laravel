<?php
/**
 * @copyright C VR Solutions 2018
 *
 * This software is the property of VR Solutions
 * and is protected by copyright law – it is NOT freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact VR Solutions:
 * E-mail: vytautas.rimeikis@gmail.com
 * http://www.vrwebdeveloper.lt
 */

declare(strict_types = 1);

use App\Article;
use App\Author;
use App\Category;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Class FakeDataSeeder
 */
class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(User::class, 'admin_user')->create();

        /** @var Collection|Article[] $articles */
        $articles = factory(Article::class, 10)->create();

        /** @var Category $categoryAll */
        $categoryAll = factory(Category::class, 'all_category')->create();

        /** @var Collection|Category[] $categories */
        $categories = factory(Category::class, 10)->create();

        $articles->each(function(Article $article) use ($categoryAll, $categories) {
            $catIds = $categories->pluck('id')->random(3)->all();

            array_push($catIds, $categoryAll->id);

            $article->categories()->attach($catIds);
        });
    }
}
