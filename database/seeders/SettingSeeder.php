<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $settings = [
            // [
            //     'key'    => 'site_logo',
            //     'value'  => null,
            //     'type'   => 'image',
            //     'details' => null,
            //     'display_name'=>'Site Logo',
            //     'group'  => 'web',
            //     'status' => 1,
            //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            //     'created_by' => 1,
            // ],
            // [
            //     'key'    => 'favicon',
            //     'value'  => null,
            //     'type'   => 'image',
            //     'details' => null,
            //     'display_name'=>'Favicon',
            //     'group'  => 'web',
            //     'status' => 1,
            //     'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            //     'created_by' => 1,
            // ],
            [

                'key'    => 'company_name',
                'value'  => 'Music Stock',
                'type'   => 'text',
                'display_name'  => 'Company Name [Login Page,Sidebar Top Title]',
                'group'  => 'web',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => 1,
            ],
            [

                'key'    => 'company_email',
                'value'  => 'musicstock@gmail.com',
                'type'   => 'text',
                'display_name'  => 'Company Email',
                'group'  => 'web',
                'details' => null,
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => 1,
            ],
            [
                'key'    => 'custom_invoice_print_message',
                'value'  =>  'Please check your Estimate Details. If there is any query , kindly contact with us.',
                'type'   => 'text',
                'details' => null,
                'display_name'=>'Custom Print-Invoice Bottom Message',
                'group'  => 'web',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => 1,
            ],

            [
                'key'    => 'shipping_amount',
                'value'  =>  150,
                'type'   => 'number',
                'details' => null,
                'display_name'=>'Shipping Amount',
                'group'  => 'web',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => 1,
            ],
            [
                'key'    => 'db_backup_email',
                'value'  =>  "ramkumawat.his@gmail.com",
                'type'   => 'text',
                'details' => null,
                'display_name'=>'DB Backup Email',
                'group'  => 'web',
                'status' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => 1,
            ],
        ];

        Setting::insert($settings);
    }
}
