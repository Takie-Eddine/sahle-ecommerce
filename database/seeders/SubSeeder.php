<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::factory(2)->create([

            'parent_id' => $this->getRandomParentId()
        ]);
    }

    private function getRandomParentId(){

        return \App\Models\Category::inRandomOrder()->first();

    }
}
