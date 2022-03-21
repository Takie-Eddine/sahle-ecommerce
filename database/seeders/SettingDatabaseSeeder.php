<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::setMany([

            'default_locale' => 'ar',
            'default_timezone' => 'Europe/Istanbul',
            'reviews_enabled' => true ,
            'auto_approve_reviews' => true ,
            'supported_currencies' => ['USD','TRY'],
            'default_currencies' => 'USD',
            'store_email' => 'yacoubdemire@outlook.fr',
            'search_engine' => 'mysql',
            'local_shipping_cost' =>0 ,
            'outer_shipping_cost' => 0 ,
            'free_shipping_cost' => 0 ,
            'translatable' => [
                
                'store_name' => 'Sahle Store',
                'local_label' => 'Local shipping' ,
                'outer_label' => 'Outer shipping' ,
                'free_shipping_label' => 'Free shipping' ,
            ]

        ]);
    }
}
