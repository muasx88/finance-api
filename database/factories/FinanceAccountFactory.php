<?php

namespace Database\Factories;

use App\Models\AccountType;
use App\Models\FinanceAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinanceAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = AccountType::select('id')->inRandomOrder()->first();
        return [
            'user_id'           => 1,
            'account_type_id'   => $type->id,
            'account_name'      => $this->faker->name(),
            'description'       => $this->faker->text()
        ];
    }
}
