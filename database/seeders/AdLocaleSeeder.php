<?php

namespace Database\Seeders;

use App\Models\FbReporting\Market;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ad_locales')->insert([
            [
               'market_id' => Market::select('id')->where('name', 'Argentina')->first()['id'],
               'locales' => '23'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Australia')->first()['id'],
                'locales' => '1001'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Austria')->first()['id'],
                'locales' => '5'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Brazil')->first()['id'],
                'locales' => '16,31'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Canada')->first()['id'],
                'locales' => '1001,44,1003,9'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Chile')->first()['id'],
                'locales' => '1002,23,7'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Colombia')->first()['id'],
                'locales' => '1002,23,7'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Denmark')->first()['id'],
                'locales' => '4'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Finland')->first()['id'],
                'locales' => '8'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'France')->first()['id'],
                'locales' => '9,44,1003'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Germany')->first()['id'],
                'locales' => '5'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Hong Kong')->first()['id'],
                'locales' => '1004,20,21,22'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'India')->first()['id'],
                'locales' => '46,45,1001,6,24,75,50,81,82,47,48,49,90'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Italy')->first()['id'],
                'locales' => '10'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Malaysia')->first()['id'],
                'locales' => '41'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Mexico')->first()['id'],
                'locales' => '1002,23,7'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Netherlands')->first()['id'],
                'locales' => '14,83'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'New Zealand')->first()['id'],
                'locales' => '1001,24,6'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Norway')->first()['id'],
                'locales' => '13,84'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Peru')->first()['id'],
                'locales' => '1002,23,7'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Philippines')->first()['id'],
                'locales' => '26,1001,6,24'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Singapore')->first()['id'],
                'locales' => '1001,24,6,41,1004,20,21,22'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Spain')->first()['id'],
                'locales' => '1002,23,7'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Sweden')->first()['id'],
                'locales' => '18'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Switzerland')->first()['id'],
                'locales' => '5,9,44,1003,10'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Taiwan')->first()['id'],
                'locales' => '20,21,22,1004'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Thailand')->first()['id'],
                'locales' => '35'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'United Kingdom')->first()['id'],
                'locales' => '24,1001,6'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'United States')->first()['id'],
                'locales' => '24,6,1001'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Venezuela')->first()['id'],
                'locales' => '23,7,1002'
            ],
            [
                'market_id' => Market::select('id')->where('name', 'Viet Nam')->first()['id'],
                'locales' => '27'
            ],
        ]);
    }
}
