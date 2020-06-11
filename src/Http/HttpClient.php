<?php

namespace IShopClient\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class HttpClient implements ClientInterface
{
    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $handler = @fopen((string)$request->getUri(), 'r', false, $this->buildContext($request));

        if (!$handler) {
            throw new ApiException(error_get_last()['message']);
        }

        return new Response(
            new Stream($handler)
        );
    }

    private function buildContext(RequestInterface $request)
    {
        $options = array(
            'http' => array(
                'method'  => $request->getMethod(),
                'content' => (string)$request->getBody(),
                'header'=> $this->buildHttpHeaders($request),
                'ignore_errors' => true
            )
        );

        return stream_context_create($options);
    }

    private function buildHttpHeaders(RequestInterface $request): string
    {
        $httpHeaders = '';

        foreach ($request->getHeaders() as $header => $values) {
            $httpHeaders .= $header . ': ' . $request->getHeaderLine($header) . "\r\n";
        }

        return $httpHeaders;
    }
}
