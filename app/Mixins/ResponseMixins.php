<?php


namespace App\Mixins;


class ResponseMixins
{
    public function success()
    {
        return function ($data = null) {
            return response()->json(['success' => true, 'data' => $data]);
        };
    }

    public function error()
    {
        return function ($property = null, $message = 'something went wrong') {
            return response()->json(['error' => ['message' => [$property => $message]]]);
        };
    }
}