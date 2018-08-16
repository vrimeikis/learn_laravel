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

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Category::class, function(Faker $faker) {
    $title = $faker->unique()->sentence(3, false);

    return [
        'title' => $title,
        'slug' => Str::slug($title),
    ];
});

$factory->defineAs(Category::class, 'all_category', function(Faker $faker) {
    return [
        'title' => 'Visos naujienos',
        'slug' => 'visos-naujienos',
    ];
});
