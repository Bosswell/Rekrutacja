<?php


namespace IShopClient\Producer;


use IShopClient\Api\ProducerApi\Response\CreateOneProducerResponse;
use IShopClient\Configuration;
use IShopClient\Http\Request;
use IShopClient\Http\Stream;
use IShopClient\Http\Uri;
use IShopClient\WebService\Producer\Request\CreateOneProducerRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class ProducerApi
{
    private ClientInterface $httpClient;
    private Configuration $configuration;

    public function __construct(ClientInterface $httpClient, Configuration $configuration)
    {
        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
    }

    public function createOne(CreateOneProducerRequest $createOneProducerRequest): CreateOneProducerResponse
    {
        $path = '/shop_api/v1/producers';;

        $request = new Request(json_encode($createOneProducerRequest), 'POST', $path);
        $response = $this->execute($request);

        return new CreateOneProducerResponse(json_decode($response));
    }

    public function getAll()
    {

    }

    private function execute(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            //
        }
    }
}
