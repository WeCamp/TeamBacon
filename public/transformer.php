<?php

require __DIR__ . '/../vendor/autoload.php';
$controller = new \Bacon\Service\Crawler\Controllers\CrawlerController();

$org = $controller->getData();
$users = $org->getMembers();


foreach ($users as $user) {

}