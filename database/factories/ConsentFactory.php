<?php

namespace Database\Factories;

use App\Models\Consent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConsentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name_en' =>  $this->faker->name,
            'full_name_ar' => $this->faker->name,
            'speciality' => $this->faker->jobTitle,
            'gov' => $this->faker->city,
            'city' =>$this->faker->city,
            'address' => $this->faker->address,
            'hospital_name' => $this->faker->name,
            'phone_number' =>$this->faker->phoneNumber,
            'email' =>$this->faker->email,
            'notes' => $this->faker->text('100'),
            'on_key_id' => $this->faker->uuid,
            'user_id'=>1
        ];
    }
}
