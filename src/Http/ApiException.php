<?php

namespace IShopClient\Http;

use Psr\Http\Client\ClientExceptionInterface;


class ApiException extends \Exception implements ClientExceptionInterface
{
}
