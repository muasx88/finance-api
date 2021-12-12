<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceAccount extends Model
{
    use SoftDeletes, HasFactory;

    protected $table    = 'finance_accounts';
    protected $fillable = ['user_id','account_type_id','account_name','description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'account_id');
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
