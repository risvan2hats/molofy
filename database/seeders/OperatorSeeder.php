<?php

namespace Database\Seeders;

use App\Modules\Operator\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        Operator::updateOrCreate(
            ['email' => 'operator1@molofy.com'],
            [
                'name' => 'Operator 1',
                'pin' => Hash::make('123456'),
            ]
        );
    }
}