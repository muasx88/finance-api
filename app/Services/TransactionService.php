<?php 
namespace App\Services;

use App\Models\FinanceTransaction;
use Illuminate\Database\QueryException;

class TransactionService
{
    public function create($request)
    {
        try {
           $trx = FinanceTransaction::create([
                'account_id'    => $request->accountId,
                'finance_name'  => $request->financeName,
                'amount'        => $request->amount,
                'description'   => $request->description
            ]);

        } catch (QueryException $e) {
            throw $e;
        }

        return $trx;
    }

    public function update($id, $user_id, $request)
    {
        try {

            $trx = FinanceTransaction::whereHas('account', function($query) use($user_id){
                $query->where('user_id',$user_id);
            })
            ->find($id);

            $trx->account_id    = $request->accountId;
            $trx->finance_name  = $request->financeName;
            $trx->amount        = $request->amount;
            $trx->description   = $request->description;
            $trx->save();

        } catch (QueryException $e) {
            throw $e;
        }

        return $trx;
    }

    public function delete($id, $user_id)
    {
        return FinanceTransaction::whereHas('account', function($query) use($user_id){
                $query->where('user_id',$user_id);
            })->where('id',$id)->delete();
    }

    public function restore($id, $user_id)
    {
        return FinanceTransaction::whereHas('account', function($query) use($user_id){
                $query->where('user_id',$user_id);
            })->where('id',$id)->restore();
    }

    public function getAll($user_id, $request)
    {
        $search     = $request->query('search');
        $account    = $request->query('account_id');
        $perpage    = $request->query('per_page') ?? 10;

        $transactions = FinanceTransaction::whereHas('account', function($query) use($user_id){
                            $query->where('user_id',$user_id);
                        })
                        ->when($account, function($query) use($account){
                            return $query->where('account_id', $account);
                        })
                        ->when($search, function($query) use($search){
                            return $query->where(function($q) use($search){
                                $q->where('finance_name','like','%'.$search.'%')
                                    ->orWhere('amount','like','%'.$search.'%')
                                    ->orWhereHas('account',function($r) use($search){
                                        $r->where('account_name','like','%'.$search.'%');
                                    });
                            });
                        })
                        ->paginate($perpage);
        return $transactions;
    }

    public function findById($id, $user_id)
    {
        return FinanceTransaction::whereHas('account', function($query) use($user_id){
            $query->where('user_id',$user_id);
        })
        ->find($id);
    }

}