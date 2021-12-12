<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Cash','Bank','eWallet'];

        foreach($types as $type) : 
            AccountType::create(['type' => $type]);
        endforeach;
    }
}
