<?php


namespace IShopClient\WebService;


use IShopClient\Http\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;


class ProducerWebService
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function createOne()
    {
        $this->httpClient->sendRequest();
    }

    public function getAll()
    {

    }

    private function buildRequest(): RequestInterface
    {
        $request = new Request();
        $request->withBody()
    }
}
