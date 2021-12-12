<?php

namespace App\Transformers;

use App\Models\FinanceTransaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'account'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FinanceTransaction $trx)
    {
        return [
            'transactionId' => (int)$trx->id,
            'financeName'   => (string)$trx->finance_name,
            'amount'        => (int)$trx->amount,
            'description'   => (string)$trx->description
        ];
    }

    public function includeAccount(FinanceTransaction $trx)
    {
        $account = $trx->account;
        return $this->item($account, new AccountTransformer);
    }
}
