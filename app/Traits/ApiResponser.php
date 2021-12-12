<?php
namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponser
{
    protected function setResponse($code, bool $status, $message, $data = null)
    {
        $response = [
            'code'      => $code,
            'status'    => $status,
            'message'   => $message,
            'results'   => $data
        ];

        if(!$data) unset($response['results']);

        return response()->json($response, $code);
    }

    protected function responseWithToken($token, $message, $data = null)
    {
        $response = [
            'code'          => 200,
            'status'        => true,
            'message'       => $message,
            'accessToken'   => $token,
            'results'       => $data
        ];

        if(!$data) unset($response['results']);

        return response()->json($response, 200);
    }

    public function responsePaginate($query_data, Collection $transform_data, $code = 200)
    {
        $results = new LengthAwarePaginator($transform_data, $query_data->total(), $query_data->perPage(), $query_data->currentPage(), [
            'path'      => request()->fullUrl(),
            'query'     => [
                'page'  => $query_data->currentPage()
            ]
        ]);
        unset($results['links']);

        // return $results;
        $response = [
            'code'      => $code,
            'status'    => true,
            'message'   => 'success',
            'results'   => $results
        ];

        return response()->json($response, $code);
    }
}
