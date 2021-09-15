<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class testFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message' => Crypt::encryptString(Str::random(5000)),
            'from_user' => random_int(1, 1000),
            'to_user' => random_int(1, 1000)
        ];
    }
}
