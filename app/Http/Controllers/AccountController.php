<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Transformers\PaginatorAdapter;
use App\Services\AccountService;
use App\Transformers\AccountTransformer;
use App\Transformers\AccountTypeTransformer;
use League\Fractal\Serializer\ArraySerializer;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    protected $account;
    public function __construct(AccountService $account)
    {
        $this->account = $account;
    }

    public function create(AccountRequest $request)
    {
        try {

            $this->account->create(auth()->user()->id, $request);

        } catch (\Exception $e) {

            return $this->setResponse(500, false, $e->getMessage());
        
        }

        return $this->setResponse(201, true, 'Finance account created successfully');
    }

    public function  update($id, AccountRequest $request)
    {
        try {

            $this->account->update($id, auth()->user()->id, $request);

        } catch (\Exception $e) {

            return $this->setResponse(500, false, $e->getMessage());
        
        }

        return $this->setResponse(200, true, 'Finance account updated successfully');
    }

    public function delete($id)
    {
        try {

            $this->account->delete($id, auth()->user()->id);

        } catch (\Exception $e) {

            return $this->setResponse(500, false, $e->getMessage());
        
        }

        return $this->setResponse(200, true, 'Finance account deleted successfully');
    }

    public function restore($id)
    {
        try {

            $this->account->restore($id, auth()->user()->id);

        } catch (\Exception $e) {

            return $this->setResponse(500, false, $e->getMessage());
        
        }

        return $this->setResponse(200, true, 'Finance account restored successfully');
    }

    public function getAll(Request $request)
    {
        $accounts = $this->account->getAll(auth()->user()->id, $request);

        $data = fractal()
                ->collection($accounts)
                ->transformWith(new AccountTransformer)
                ->toArray();

        return $this->responsePaginate($accounts, collect($data), 200);
    }

    public function detail($id)
    {
        $account = $this->account->findById($id, auth()->user()->id);
        if(!$account) return $this->setResponse(404, false, 'Finance account not found');

        $data = fractal()->item($account)->transformWith(new AccountTransformer)->toArray();

        return $this->setResponse(200, true, 'detail finance account', $data);
    }

    public function getAccountType()
    {
        $types = $this->account->getAccountType();

        $data = fractal()->collection($types)->transformWith(new AccountTypeTransformer)->toArray();

        return $this->setResponse(200, true, 'account type', $data);
    }
}
