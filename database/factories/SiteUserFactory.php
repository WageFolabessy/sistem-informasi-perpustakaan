<?php

namespace Database\Factories;

use App\Models\SiteUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiteUserFactory extends Factory
{
    protected $model = SiteUser::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'class' => $this->faker->randomElement(['X', 'XI', 'XII']) . ' ' . $this->faker->randomElement(['TKJ 1', 'AKL 2', 'OTKP 1', 'BDP 3']),
            'major' => $this->faker->randomElement(['Teknik Komputer & Jaringan', 'Akuntansi Keuangan Lembaga', 'Otomatisasi Tata Kelola Perkantoran', 'Bisnis Daring Pemasaran']),
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'fcm_token' => null,
            'is_active' => $this->faker->boolean(80),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
