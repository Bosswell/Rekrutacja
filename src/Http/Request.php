<?php

namespace IShopClient\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class Request implements RequestInterface
{
    use MessageTrait;

    private StreamInterface $body;
    private string $method = '';
    private UriInterface $uri;
    private ?string $requestTarget;

    public function __construct(string $method, string $url, string $body = '', array $headers = [], string $version = '1.1')
    {
        $this->body = new Stream(
            fopen('php://memory','r+')
        );
        $this->body->write($body);
        $this->body->rewind();
        $this->method = $method;
        $this->uri = new Uri($url);
        $this->version = $version;
        $this->requestTarget = null;
        $this->setHeaders($headers);
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException(
                'Invalid request target provided; cannot contain whitespace'
            );
        }

        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
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
        $this->assertMethod($method);
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
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
        if ($uri === $this->uri) {
            return $this;
        }

        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !isset($this->headerNames['host'])) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    private function updateHostFromUri(): void
    {
        $host = $this->uri->getHost();

        if ($host == '') {
            return;
        }

        if (($port = $this->uri->getPort()) !== null) {
            $host .= ':' . $port;
        }

        if (isset($this->headerNames['host'])) {
            $header = $this->headerNames['host'];
        } else {
            $header = 'Host';
            $this->headerNames['host'] = 'Host';
        }
        // Ensure Host is the first header.
        // See: http://tools.ietf.org/html/rfc7230#section-5.4
        $this->headers = [$header => [$host]] + $this->headers;
    }

    /**
     * @param mixed $method
     */
    private function assertMethod($method): void
    {
        if (!is_string($method) || $method === '') {
            throw new \InvalidArgumentException('Method must be a non-empty string.');
        }
    }
}
