<?php

namespace Database\Factories;

use App\Models\Backend\RoleManagement\Permission;
use App\Models\Backend\RoleManagement\PermissionCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'slug' => $this->faker->slug,
            'is_default' => $this->faker->numberBetween(0, 127),
            'status' => $this->faker->numberBetween(0, 127),
            'permission_category_id' => PermissionCategory::factory(),
        ];
    }
}
