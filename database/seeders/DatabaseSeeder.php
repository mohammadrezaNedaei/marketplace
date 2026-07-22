<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'ادمین تست'],
            [
                'phone'    => '09123456777',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::firstOrCreate(
            ['username' => 'فروشنده تست'],
            [
                'phone'    => '09123456778',
                'password' => Hash::make('seller123'),
                'role'     => 'seller',
            ]
        );

        User::firstOrCreate(
            ['username' => 'خریدار تست'],
            [
                'phone'    => '09123456779',
                'password' => Hash::make('buyer123'),
                'role'     => 'buyer',
            ]
        );


        $categories = [
            'طراحی گرافیک',
            'موزیک',
            'برنامه‌ نویسی',
            'عکاسی',
            'آموزش',
            'کتاب الکترونیک',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}