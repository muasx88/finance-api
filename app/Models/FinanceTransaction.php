<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    use SoftDeletes, HasFactory;

    protected $table    = 'finance_transactions';
    protected $fillable = ['account_id','finance_name','amount','description'];

    public function account()
    {
        return $this->belongsTo(FinanceAccount::class, 'account_id');
    }
}
