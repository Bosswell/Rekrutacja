<?php

require 'vendor/autoload.php';

use IShopClient\Api\ProducerApi\ProducerApi;
use IShopClient\Api\ProducerApi\Request\CreateOneProducerRequest;
use IShopClient\Configuration;
use IShopClient\Http\HttpClient;
use IShopClient\Model\Producer;

$httpClient = new HttpClient();
$configuration = new Configuration('http://rekrutacja.localhost:8091', 'rest', 'vKTUeyrt1!');

$producerApi = new ProducerApi($httpClient, $configuration);

// Create producer
$producer = new Producer(5, 'John', 'site_url', 'filename', 11, '2222');
$request = new CreateOneProducerRequest($producer);
$response = $producerApi->createOne($request);
print_r($response);
//print_r($producerApi->getAll());
//// Get All producers
//
