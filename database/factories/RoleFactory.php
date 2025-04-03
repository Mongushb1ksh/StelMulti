<?php

namespace Database\Factories;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'admin', 'user', 'sales_manager', 'production_manager',
                'warehouse_worker', 'accountant', 'director', 'technician', 'client'
            ]),
        ];
    }
}
