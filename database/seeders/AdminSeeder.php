<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin= User::create([
        'name'=>'bashar',
        'email'=>'basharsh.191@gmial.com',
        'password'=>Hash::make('11223344'),
        'gender'=>'male',
        'birth_date'=>'1-1-1990'
        ]);
        $admin->assignRole('admin');

        $patient= User::create([
        'name'=>'bashar',
        'email'=>'basharsh.391@gmial.com',
        'password'=>Hash::make('11223344'),
        'gender'=>'male',
        'birth_date'=>'1-1-1990'
        ]);
        $patient->assignRole('doctor');
    }
}
