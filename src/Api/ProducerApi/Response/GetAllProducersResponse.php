<?php

namespace IShopClient\Api\ProducerApi\Response;

use IShopClient\Api\AbstractResponse;
use IShopClient\Model\Producer;


class GetAllProducersResponse extends AbstractResponse
{
    /** @var Producer[]|null */
    private ?array $data = null;

    public function __construct(array $response)
    {
        parent::__construct($response);

        if ($producers = $response['data'] ?? null) {
            foreach ($producers as $producer) {
                $this->data[] = new Producer(
                    (int)$producer['id'],
                    $producer['name'] ?? '',
                    $producer['site_url'] ?? '',
                    $producer['logo_filename'] ?? '',
                    (int)$producer['ordering'],
                    $producer['source_id'] ?? ''
                );
            }
        }
    }
}

