<?php


namespace IShopClient\Api;


use IShopClient\Model\Error;

abstract class AbstractResponse
{
    private string $version;
    private bool $success;
    private ?Error $error;

    public function __construct(array $response)
    {
        $this->version = (string)$response['version'] ?? '';
        $this->success = (bool)$response['success'] ?? false;
        $this->error = $response['error'] ?? null;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return Error|null
     */
    public function getError(): ?Error
    {
        return $this->error;
    }
}
