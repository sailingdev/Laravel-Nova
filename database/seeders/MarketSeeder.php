<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('markets')->insert([
            [
               'name' => 'Argentina',
               'code' => 'AR'
            ],
            [
                'name' => 'Austria',
                'code' => 'AT'
            ],
            [
                'name' => 'Australia',
                'code' => 'AU'
            ],
            [
                 'name' => 'Brazil',
                 'code' => 'BR'
            ],
            [
                'name' => 'Canada',
                'code' => 'CA'
             ],
             [
                 'name' => 'Chile',
                 'code' => 'CL'
             ],
             [
                 'name' => 'Colombia',
                 'code' => 'CO'
             ],
             [
                  'name' => 'Germany',
                  'code' => 'DE'
             ],
             [
                'name' => 'Denmark',
                'code' => 'DK'
             ],
             [
                 'name' => 'Finland',
                 'code' => 'FI'
             ],
             [
                  'name' => 'France',
                  'code' => 'FR'
             ],
             [
                'name' => 'Hong Kong',
                'code' => 'HK'
             ],
             [
                 'name' => 'India',
                 'code' => 'IN'
             ],
             [
                 'name' => 'Italy',
                 'code' => 'IT'
             ],
             [
                  'name' => 'Malaysia',
                  'code' => 'MY'
             ],
             [
                'name' => 'Netherlands',
                'code' => 'NL'
             ],
             [
                 'name' => 'Norway',
                 'code' => 'NO'
             ],
             [
                 'name' => 'New Zealand',
                 'code' => 'NZ'
             ],
             [
                'name' => 'Peru',
                'code' => 'PE'
             ],
             [
                  'name' => 'Philippines',
                  'code' => 'PH'
             ],
            [
                'name' => 'Singapore',
                'code' => 'SG'
            ],
            [
                'name' => 'Spain',
                'code' => 'ES'
            ],
            [
                'name' => 'Sweden',
                'code' => 'SE'
            ],
            [
                'name' => 'Switzerland',
                'code' => 'CH'
            ],
            [
                'name' => 'Taiwan',
                'code' => 'TW'
            ],
            [
                'name' => 'Thailand',
                'code' => 'TH'
            ],
            [
                'name' => 'Mexico',
                'code' => 'MX'
            ],
            [
                'name' => 'United Kingdowm',
                'code' => 'UK'
            ],
            [
                'name' => 'United States',
                'code' => 'US'
            ],
            [
                'name' => 'Venezuela',
                'code' => 'VE'
            ],
            [
                'name' => 'Viet Nam',
                'code' => 'VN'
            ],
        ]);
    }
}
