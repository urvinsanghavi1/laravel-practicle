<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\MultiTenantProcess;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use MultiTenantProcess;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => $this->encrypt_decrypt("encrypt", "password"), // password
        ];
    }
}
