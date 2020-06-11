<?php

namespace IShopClient\Api;

use Psr\Http\Client\ClientExceptionInterface;


class ApiException extends \Exception implements ClientExceptionInterface
{
}
