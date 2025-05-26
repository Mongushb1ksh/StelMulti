<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Production Worker'],
            ['name' => 'Warehouse Manager'],
            ['name' => 'Client Manager'],
            ['name' => 'null'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@steelmulti.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Production Worker',
            'email' => 'worker@steelmulti.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Warehouse Manager',
            'email' => 'warehouse@steelmulti.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'Client Manager',
            'email' => 'client@steelmulti.com',
            'password' => Hash::make('password'),
            'role_id' => 4,
        ]);
    }
}