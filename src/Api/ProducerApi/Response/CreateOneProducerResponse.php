<?php


namespace IShopClient\Api\ProducerApi\Response;

use IShopClient\Api\AbstractResponse;
use IShopClient\Model\Producer;


class CreateOneProducerResponse extends AbstractResponse
{
    private ?Producer $data;

    public function __construct(array $response)
    {
        parent::__construct($response);

        $this->data = $response['data'];
    }
}
