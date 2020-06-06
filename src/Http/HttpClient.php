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
        $stream = new Stream(
            fopen((string)$request->getUri(), 'r', false, $this->buildContext($request))
        );

        return new Response($stream);
    }

    private function buildContext(RequestInterface $request)
    {
        $options = array(
            'http' => array(
                'method'  => $request->getMethod(),
                'content' => $request->getBody()->getContents(),
                'header'=> $request->getHeaders()
            )
        );

        return stream_context_create($options);
    }
}
