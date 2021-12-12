<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $trx;
    public function __construct(TransactionService $trx)
    {
        $this->trx = $trx;
    }

    public function create(TransactionRequest $request)
    {
        try {

            $this->trx->create($request);

        } catch (\Exception $e) {
            return $this->setResponse(500, false, $e->getMessage());
        }

        return $this->setResponse(201, true, 'transaction created successfully');
    }

    public function update($id, TransactionRequest $request)
    {
        try {

            $this->trx->update($id, auth()->user()->id, $request);

        } catch (\Exception $e) {
            return $this->setResponse(500, false, $e->getMessage());
        }

        return $this->setResponse(201, true, 'transaction updated successfully');
    }

    public function delete($id)
    {
        try {

            $this->trx->delete($id, auth()->user()->id);

        } catch (\Exception $e) {
            return $this->setResponse(500, false, $e->getMessage());
        }

        return $this->setResponse(201, true, 'transaction deleted successfully');
    }

    public function restore($id)
    {
        try {

            $this->trx->restore($id, auth()->user()->id);

        } catch (\Exception $e) {
            return $this->setResponse(500, false, $e->getMessage());
        }

        return $this->setResponse(201, true, 'transaction restored successfully');
    }

    public function getAll(Request $request)
    {
        $transactions = $this->trx->getAll(auth()->user()->id, $request);

        $data = fractal()
                ->collection($transactions)
                ->transformWith(new TransactionTransformer)
                ->toArray();

        return $this->responsePaginate($transactions, collect($data), 200);

    }

    public function detail($id)
    {
        $transaction = $this->trx->findById($id, auth()->user()->id);
        if(!$transaction) return $this->setResponse(404, true, 'trasaction not found');

        $data = fractal()
                ->item($transaction)
                ->transformWith(new TransactionTransformer)
                ->toArray();

        return $this->setResponse(200, true, 'detail transaction', $data);
    }

}
