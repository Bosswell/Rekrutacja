<?php


namespace IShopClient\Model;


class Error
{
    private string $reason_code;
    private array $messages;

    public function __construct(array $data)
    {
        $this->reason_code = (string)$data['reason_code'] ?? '';
        $this->messages = $data['messages'] ?? [];
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
}
