<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Database\Factories\RoleFactory;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создание ролей через фабрику
        Role::factory()->count(9)->create();
    }
}