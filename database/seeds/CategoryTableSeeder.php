<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $count = DB::table('categories')->count();
        $currentDateTime = \Carbon\Carbon::now();
        if ($count == 0) {
            DB::table('categories')->insert(array(
                array(
                    'name' => 'Security',
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ),
                array(
                    'name' => 'Health & Safety',
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ),
                array(
                    'name' => 'Loss Prevention',
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ),
            ));
        }
    }

}
