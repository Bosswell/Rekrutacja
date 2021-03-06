<?php

namespace IShopClient\Api\ProducerApi\Request;

use IShopClient\Model\Producer;
use JsonSerializable;


class CreateOneProducerRequest implements JsonSerializable
{
    private Producer $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function jsonSerialize()
    {
        return [
            'producer' => [
                'id' => $this->producer->getId(),
                'name' => $this->producer->getName(),
                'site_url' => $this->producer->getSiteUrl(),
                'logo_filename' => $this->producer->getLogoFilename(),
                'ordering' => $this->producer->getOrdering(),
                'source_id' => $this->producer->getSourceId()
            ]
        ];
    }
}
