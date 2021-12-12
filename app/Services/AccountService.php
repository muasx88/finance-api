<?php 
namespace App\Services;

use App\Models\AccountType;
use App\Models\FinanceAccount;
use Illuminate\Database\QueryException;

class AccountService
{
    public function create($user_id, $request)
    {
        try {

            $account = FinanceAccount::create([
                'user_id'           => $user_id,
                'account_type_id'   => $request->accountTypeId,
                'account_name'      => $request->accountName,
                'description'       => $request->description
            ]);

        } catch (QueryException $e) {
            throw $e;
        }

        return $account;
    }

    public function update($id, $user_id, $request)
    {
        try {

            $account = FinanceAccount::where('user_id',$user_id)->find($id);
            $account->account_type_id = $request->accountTypeId;
            $account->account_name    = $request->accountName;
            $account->description     = $request->description; 
            $account->save();

        } catch (QueryException $e) {
            throw $e;
        }

        return $account;
    }

    public function delete($id, $user_id)
    {
        return FinanceAccount::where(['id' => $id,'user_id' => $user_id])->delete();
    }

    public function getAll($user_id, $request)
    {
        $search     = $request->query('search');
        $type_id       = $request->query('type_id');
        $perpage    = $request->query('per_page') ?? 10;

        $accounts = FinanceAccount::where('user_id', $user_id)
                    ->when($type_id, function($query) use($type_id){
                        return $query->where('account_type_id', $type_id);
                    })
                    ->when($search, function($query) use($search){
                        return $query->where(function($q) use($search){
                            $q->where('account_name','like','%'.$search.'%')
                             ->orWhere('description','like','%'.$search.'%');
                        });
                    })
                    ->paginate($perpage);
        
        return $accounts;
    }

    public function findById($id, $user_id)
    {
        return FinanceAccount::where('user_id', $user_id)->find($id);
    }

    public function getAccountType()
    {
        return AccountType::select('id','type')->get();
    }

    public function restore($id, $user_id)
    {
        return FinanceAccount::where(['user_id' => $user_id, 'id' => $id])->restore();
    }
}