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

declare(strict_types =1);

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        $languages = ['Visos', 'PHP', 'JavaScript', 'HTML5', 'C#', 'Python'];

        $data = [];

        foreach ($languages as $language) {
            array_push($data, [
                'title' => $language,
                'slug' => Str::slug($language),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('categories')->insert($data);
    }
}
