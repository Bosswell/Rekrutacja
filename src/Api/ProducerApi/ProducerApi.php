<?php


namespace IShopClient\Producer;


use IShopClient\Api\ProducerApi\Response\CreateOneProducerResponse;
use IShopClient\Http\Request;
use IShopClient\Http\Stream;
use IShopClient\WebService\Producer\Request\CreateOneProducerRequest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class ProducerApi
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function createOne(CreateOneProducerRequest $createOneProducerRequest): CreateOneProducerResponse
    {
        $url = '/shop_api/v1/producers';;
        $stream = new Stream(
            fopen('php://memory','r+')
        );

        $request = $this->buildRequest(json_encode($createOneProducerRequest));

        $response = $this->httpClient->sendRequest();

        $response = $this->execute();

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
            $exception->
        }
    }

    private function buildRequest(string $body): RequestInterface
    {
        $request = new Request();
        $stream = new Stream(
            fopen('php://memory','r+')
        );
        $stream->write($body);

        $request->withBody($stream);
        $request->withUri()
    }
}
