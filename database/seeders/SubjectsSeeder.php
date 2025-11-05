<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        Subject::updateOrCreate(['name' => 'Matemáticas'], [
            'icon' => '🧮',
            'description' => 'Operaciones básicas, álgebra, geometría',
            'is_active' => true,
        ]);

        Subject::updateOrCreate(['name' => 'Español'], [
            'icon' => '📚',
            'description' => 'Lectura, gramática, comprensión',
            'is_active' => true,
        ]);
    }
}
