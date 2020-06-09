<?php


namespace IShopClient\Api\ProducerApi\Response;

use IShopClient\Api\AbstractResponse;
use IShopClient\Model\Producer;


class CreateOneProducerResponse extends AbstractResponse
{
    private ?Producer $data = null;

    public function __construct(array $response)
    {
        parent::__construct($response);

        if ($producer = $response['data'] ?? null) {
            $this->data = new Producer(
                (int)$producer['id'],
                $producer['name'],
                $producer['site_url'],
                $producer['logo_filename'],
                (int)$producer['ordering'],
                $producer['source_id'],
            );
        }
    }
}
