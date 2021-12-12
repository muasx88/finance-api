<?php

namespace App\Transformers;

use League\Fractal\Serializer\DataArraySerializer;

class ResponseSerialize extends DataArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }

        return $data;
    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }
        return $data;
    }
}