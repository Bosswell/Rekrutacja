<?php

namespace IShopClient\Model;

class Error
{
    private string $reason_code;
    private string $singleMessage;
    private array $messages;

    public function __construct(array $data)
    {
        $this->reason_code = (string)($data['reason_code'] ?? '');
        $this->messages = $data['messages'] ?? [];
        $this->singleMessage = $data['singleMessage'] ?? '';
    }

    /**
     * @return string
     */
    public function getReasonCode(): string
    {
        return $this->reason_code;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function getSingleMessage(): string
    {
        return $this->singleMessage;
    }
}
