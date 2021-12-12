<?php

namespace App\Transformers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'type'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'transactions'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FinanceAccount $account)
    {
        return [
            'financeAccountId' => (int)$account->id,
            'accountName'      => (string)$account->account_name,
            'description'      => (string)$account->description
        ];
    }

    public function includeTransactions(FinanceTransaction $account)
    {
        $transactions = $account->transactios;

        return $this->collection($transactions, new TransactionTransformer);
    }

    public function includeType(FinanceAccount $account)
    {
        $type = $account->type;

        return $this->item($type, new AccountTypeTransformer);
    }
}
