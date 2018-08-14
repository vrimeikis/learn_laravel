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

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        $authors = [
            'Jonas Jonaitis',
            'Petras Petraitis',
            'Juozas Juozaitis',
        ];

        $data = [];

        foreach ($authors as $author) {
            list($firstName, $lastName) = explode(' ', $author);

            array_push($data, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('authors')->insert($data);

    }
}
