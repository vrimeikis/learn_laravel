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
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Article::class, function(Faker $faker) {
    $title = $faker->unique()->sentence(6);

    return [
        'title' => $title,
        'cover' => 'articles/' . $faker->file(
                resource_path('img'),
                storage_path('app/public/articles'),
                false
            ),
        'description' => $faker->paragraph,
        'slug' => Str::slug($title),
        'author_id' => function() {
            return factory(Author::class)->create();
        },
    ];
});
