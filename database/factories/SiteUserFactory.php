<?php

namespace Database\Factories;

use App\Models\SiteUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SiteUser>
 */
class SiteUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SiteUser::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('##########'), // Contoh 10 digit NIS unik
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'), // Default password 'password'
            'class' => $this->faker->randomElement(['X', 'XI', 'XII']) . ' ' . $this->faker->randomElement(['TKJ 1', 'AKL 2', 'OTKP 1', 'BDP 3']), // Contoh kelas
            'major' => $this->faker->randomElement(['Teknik Komputer & Jaringan', 'Akuntansi Keuangan Lembaga', 'Otomatisasi Tata Kelola Perkantoran', 'Bisnis Daring Pemasaran']), // Contoh jurusan
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'fcm_token' => null, // Default null
            'is_active' => $this->faker->boolean(80), // 80% kemungkinan aktif secara default
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the user account is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user account is inactive (pending).
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user has an FCM token.
     */
    public function withFcmToken(): static
    {
        return $this->state(fn(array $attributes) => [
            'fcm_token' => Str::random(152), // Contoh token FCM acak
        ]);
    }
}
