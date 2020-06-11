<?php

namespace IShopClient\Api\ProducerApi;

use IShopClient\Api\ProducerApi\Request\CreateOneProducerRequest;
use IShopClient\Api\ProducerApi\Response\CreateOneProducerResponse;
use IShopClient\Api\ProducerApi\Response\GetAllProducersResponse;
use IShopClient\Configuration;
use IShopClient\Http\ApiException;
use IShopClient\Http\Request;
use IShopClient\Http\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;


class ProducerApi
{
    private ClientInterface $httpClient;
    private Configuration $configuration;
    private string $encodedAuth;

    public function __construct(ClientInterface $httpClient, Configuration $configuration)
    {
        $this->httpClient = $httpClient;
        $this->configuration = $configuration;
        $this->encodedAuth = base64_encode($this->configuration->getUsername() . ':' . $this->configuration->getPassword());
    }

    /**
     * @return CreateOneProducerResponse
     * @throws Throwable
     * @param CreateOneProducerRequest $createOneProducerRequest
     */
    public function createOne(CreateOneProducerRequest $createOneProducerRequest): CreateOneProducerResponse
    {
        return $this->execute(
            $this->buildRequest('POST', '/shop_api/v1/producers', json_encode($createOneProducerRequest)),
            CreateOneProducerResponse::class
        );
    }

    /**
     * @return mixed
     * @throws Throwable
     * @return GetAllProducersResponse
     */
    public function getAll(): GetAllProducersResponse
    {
        return $this->execute(
            $this->buildRequest('GET', '/shop_api/v1/producers'),
            GetAllProducersResponse::class
        );
    }

    /**
     * @throws Throwable
     * @param RequestInterface $request
     * @param string $responseClass
     */
    private function execute(RequestInterface $request, string $responseClass)
    {
        $response = $this->httpClient->sendRequest($request);
        $body = (string)$response->getBody();
        $decodedBody = json_decode($body, true);
        $code = $response->getStatusCode();

        if (is_array($decodedBody) && !empty($decodedBody)) {
            if (key_exists('exception', $decodedBody)) {
                throw new ApiException($body, $response->getStatusCode());
            }

            return new $responseClass(['data' => $decodedBody]);
         }

        if ($code >= 400) {
            if (empty($decodedBody)) {
                if ($code === Response::UNAUTHORIZED_HTTP_CODE) {
                    throw new ApiException('Unauthorized request', $code);
                }

                throw new ApiException('Unknown error', $code);
            }

            return new $responseClass(['error' => ['singleMessage' => $body]]);
        }

        throw new \LogicException('Response body is not an array and seems to not have any error or success code');
    }

    private function buildRequest(string $method, string $url, string $body = '', array $headers = []): RequestInterface
    {
        $url = $this->configuration->getHost() . $url;
        $headers = array_merge($headers, [
            'Authorization' => 'Basic ' . $this->encodedAuth,
            'Content-Type' => 'application/json'
        ]);

        return new Request($method, $url, $body, $headers);
    }
}
