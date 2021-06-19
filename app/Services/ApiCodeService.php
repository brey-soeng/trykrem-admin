<?php


namespace App\Services;


use Kayex\HttpCodes;

class ApiCodeService extends HttpCodes
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_RESET_CONTENT = 205;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_TOO_MANY_REQUEST = 429;
    const HTTP_TOKEN_EXPIRED = 430;
}
