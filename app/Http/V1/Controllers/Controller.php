<?php

namespace App\Http\V1\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public const HTTP_OK = 200;

    public const HTTP_CREATED = 201;

    public const HTTP_ACCEPTED = 202;

    public const HTTP_BUSINESS_ERROR = 280;

    public const HTTP_BAD_REQUEST = 400;

    public const HTTP_UNAUTHORIZED = 401;

    public const HTTP_INTERNAL_SERVER_ERROR = 500;
}
