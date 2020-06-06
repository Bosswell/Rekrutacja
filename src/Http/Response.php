<?php


namespace IShopClient\Http;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;


class Response implements ResponseInterface
{
    use MessageTrait;

    private StreamInterface $body;
    private ?string $reasonPhrase = null;
    private int $code;

    public function __construct(StreamInterface $body)
    {
        $this->body = $body;
        $this->code = $this->findHttpCode();
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        if (!is_int($code)) {
            throw new \TypeError('Given code need to be integer');
        }

        if ($code === $this->code) {
            return $this;
        }

        $response = clone $this;
        $response->code = $code;

        return $response;
    }

    public function getReasonPhrase(): ?string
    {
        return $this->reasonPhrase;
    }

    private function findHttpCode(): int
    {
        $httpInfoLine = $this->body->getMetadata('wrapper_data')[0];

        return (int)explode(' ', $httpInfoLine)[1];
    }
}
