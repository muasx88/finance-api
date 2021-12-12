<?php

namespace App\Transformers;

use App\Models\AccountType;
use League\Fractal\TransformerAbstract;

class AccountTypeTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
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
    public function transform(AccountType $type)
    {
        return [
            'accountTypeId' => (int)$type->id,
            'accountType'   => (string)$type->type
        ];
    }
}
