<?php


namespace IShopClient\Http;


use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @see MessageInterface
 */
trait MessageTrait
{
    private string $version;
    private array $headers;
    private array $headerNames;
    private StreamInterface $body;


    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    public function withProtocolVersion($version): self
    {
        if ($this->version === $version) {
            return $this;
        }

        $message = clone $this;
        $message->version = $version;

        return $message;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return in_array(strtolower($name), $this->headerNames);
    }

    public function getHeader($name)
    {
        if ($this->hasHeader($name)) {
            return $this->headers[strtolower($name)];
        }

        return [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value)
    {
        $message = clone $this;
        $message->headers[strtolower($name)] = $value;

        return $message;
    }

    public function withAddedHeader($name, $values)
    {

    }

    public function withoutHeader($name)
    {

    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        if ($this->body === $body) {
            return $this;
        }

        $message = clone $this;
        $message->body = $body;

        return $message;
    }
}
