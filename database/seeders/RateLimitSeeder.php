<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'group_name' => 'rate-limit',
            'settings_name' => 'limit',
            'value' => 3,
            'created_at' => Carbon::today(),
            'updated_at' => Carbon::today(),
        ]);
    }
}
