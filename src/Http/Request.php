<?php


namespace IShopClient\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class Request implements RequestInterface
{
    use MessageTrait;

    private string $version = '';
    private StreamInterface $body;
    private array $headers = [];
    private string $method = '';
    private UriInterface $uri;

    public function __construct(string $body, string $method, string $path)
    {
        $this->body = new Stream(
            fopen('php://memory','r+')
        );
        $this->body->write($body);
        $this->method = $method;
        $this->uri = new Uri($path);
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method): self
    {
        $this->method = strtoupper($method);
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false): self
    {

    }
}
