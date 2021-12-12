<?php

namespace Database\Factories;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinanceTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $account = FinanceAccount::inRandomOrder()->first();
        return [
            'account_id'    => $account->id,
            'finance_name'  => $this->faker->name(),
            'amount'        => rand(1,100) . '0000',
            'description'   => $this->faker->text()
        ];
    }
}
