<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ModelHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_permissions')->insert([
            'permission_id' => Permission::where('name', 'can_view_fb_page_resource')->first()['id'],
            'model_type' => 'App\Models\User',
            'model_id' => User::where('email', 'admin@revenuedriver.com')->first()['id']
        ]);
    }
}
