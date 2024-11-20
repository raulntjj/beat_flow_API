<?php

namespace App\Http\Controllers;
use App\Traits\S3Operations;
use Illuminate\Http\Request;

class Controller {
    use S3Operations;
    public function teste(Request $request) {
        $response = $this->storePostMedia($request);
        dd($response);
    }
}
