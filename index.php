<?php

use IShopClient\Configuration;
use IShopClient\Http\HttpClient;
use IShopClient\Model\Producer;
use IShopClient\Producer\ProducerApi;
use IShopClient\WebService\Producer\Request\CreateOneProducerRequest;

$httpClient = new HttpClient();
$configuration = new Configuration('https://strona.pl', 'Admin', 'pass');

$producerApi = new ProducerApi($httpClient, $configuration);

// Create producer
$producer = new Producer('John', 'site_url', 'filename', 11, '222');
$request = new CreateOneProducerRequest($producer);
$producerApi->createOne($request);

// Get All producers
$producerApi->getAll();
