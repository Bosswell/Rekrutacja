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
        return array_key_exists(strtolower($name), $this->headerNames);
    }

    public function getHeader($name)
    {
        if ($this->hasHeader($name)) {
            return $this->headers[$this->headerNames[strtolower($name)]];
        }

        return [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): self
    {
        $message = clone $this;
        $normalized = strtolower($name);
        $message->headers[$normalized] = !is_array($value) ? [$value] : $value;
        $message->headerNames[] = $normalized;

        return $message;
    }

    public function withAddedHeader($name, $value)
    {
        $this->assertHeader($name);
        $value = $this->normalizeHeaderValue($value);
        $normalized = strtolower($name);

        $message = clone $this;
        if (isset($message->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $message->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $message->headerNames[$normalized] = $name;
            $message->headers[$name] = $value;
        }

        return $message;
    }

    public function withoutHeader($name)
    {
        $normalized = strtolower($name);

        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];

        $message = clone $this;
        unset($message->headers[$header], $message->headerNames[$normalized]);

        return $message;
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

    private function setHeaders(array $headers)
    {
        $this->headerNames = $this->headers = [];
        foreach ($headers as $header => $value) {
            if (is_int($header)) {
                $header = (string) $header;
            }

            $this->assertHeader($header);
            $value = $this->normalizeHeaderValue($value);
            $normalized = strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }

    /**
     * @param mixed $value
     *
     * @return string[]
     */
    private function normalizeHeaderValue($value): array
    {
        if (!is_array($value)) {
            return $this->trimHeaderValues([$value]);
        }

        if (count($value) === 0) {
            throw new \InvalidArgumentException('Header value can not be an empty array.');
        }

        return $this->trimHeaderValues($value);
    }

    private function trimHeaderValues(array $values): array
    {
        return array_map(function ($value) {
            if (!is_scalar($value) && null !== $value) {
                throw new \InvalidArgumentException(sprintf(
                    'Header value must be scalar or null but %s provided.',
                    is_object($value) ? get_class($value) : gettype($value)
                ));
            }

            return trim((string) $value, " \t");
        }, array_values($values));
    }

    /**
     * @see https://tools.ietf.org/html/rfc7230#section-3.2
     *
     * @param mixed $header
     */
    private function assertHeader($header): void
    {
        if (!is_string($header)) {
            throw new \InvalidArgumentException(sprintf(
                'Header name must be a string but %s provided.',
                is_object($header) ? get_class($header) : gettype($header)
            ));
        }

        if (! preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/', $header)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '"%s" is not valid header name',
                    $header
                )
            );
        }
    }
}
