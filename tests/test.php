<?php


use Kudu\CTKudu\Endpoints\LocationsEndpoint;

$locations = app(LocationsEndpoint::class);

$data = $locations->get(['activeFlag' => true,'corporate' => false]);
