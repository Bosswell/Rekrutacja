<?php

namespace IShopClient\Http;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;


class Uri implements UriInterface
{
    private string $host;
    private string $url;
    private string $scheme;
    private ?int $port;
    private string $path;
    private string $query;
    private string $userInfo;
    private string $fragment;
    private string $fullUrl;

    public function __construct(string $url)
    {
        $this->url = $url;
        $urlInfo = parse_url($url);
        $this->scheme = $urlInfo['scheme'] ?? '';
        $this->path = $urlInfo['path'] ?? '';
        $this->query = $urlInfo['query'] ?? '';
        $this->userInfo = $urlInfo['user'] ?? '';

        if ($pass = $urlInfo['pass'] ?? null) {
            $this->userInfo .= ':' . $pass;
        }

        $this->fragment = $urlInfo['fragment'] ?? '';
        $this->host = $urlInfo['host'] ?? '';
        $this->host =  preg_replace('/ /', '', $this->host);
        $this->port = ($urlInfo['port'] ?? null);

        if (!$this->isHostValid($this->host)) {
            throw new \InvalidArgumentException('Invalid hostname');
        }

        if (($port = $urlInfo['port'] ?? null) && $this->isPortValid($port)) {
            $this->port = (int)$port;
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        if (!isset($this->fullUrl)) {
            $this->fullUrl = $this->createFullUrl();
        }

        return $this->fullUrl;
    }

    /**
     * @inheritDoc
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = $this->host;
        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme): self
    {
        if ($this->userInfo === $scheme) {
            return $this;
        }

        $uri = clone $this;
        $uri->scheme = $scheme;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null): self
    {
        if (!is_null($password)) {
            $user .= ':' . $password;
        }

        if ($this->userInfo === $user) {
            return $this;
        }


        $uri = clone $this;
        $uri->userInfo = $user;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host): self
    {
        if ($this->host === $host) {
            return $this;
        }

        if (!$this->isHostValid($host)) {
            throw new InvalidArgumentException('Invalid hostname');
        }

        $uri = clone $this;
        $uri->host = $host;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withPort($port): self
    {
        if ($this->port === $port) {
            return $this;
        }

        if (!$this->isPortValid($port)) {
            throw new InvalidArgumentException('Given port is invalid');
        }

        $uri = clone $this;
        $uri->port = $port;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withPath($path): self
    {
        if ($this->path === $path) {
            return $this;
        }

        $uri = clone $this;
        $uri->path = $path;

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query): self
    {
        if ($this->query === $query) {
            return $this;
        }

        $uri = clone $this;
        $uri->query = $this->filterQueryAndFragment($query);

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment): self
    {
        if ($this->fragment === $fragment) {
            return $this;
        }

        $uri = clone $this;
        $uri->fragment = $this->filterQueryAndFragment($fragment);

        return $uri;
    }

    private function isPortValid($port): bool
    {
        if (!is_int($port) || $port < 0 || $port > 65535) {
            return false;
        }

        return true;
    }

    private function filterQueryAndFragment(string $string): string
    {
        return urlencode(urldecode($string));
    }

    private function isHostValid(string $host): bool
    {
        return (bool)filter_var($host, FILTER_VALIDATE_DOMAIN);
    }

    private function createFullUrl(): string
    {
        $uri = '';

        if ($this->getScheme() != '') {
            $uri .= $this->getScheme() . ':';
        }

        if ($this->getAuthority() != ''|| $this->scheme === 'file') {
            $uri .= '//' . $this->getAuthority();
        }

        $uri .= $this->getPath();

        if ($this->getQuery() != '') {
            $uri .= '?' . $this->getQuery();
        }

        if ($this->getFragment() != '') {
            $uri .= '#' . $this->getFragment();
        }

        return $uri;
    }
}
