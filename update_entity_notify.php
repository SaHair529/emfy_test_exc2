<?php

use Amocrm\AmoApi;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/autoload.php';

parse_str(file_get_contents('php://input'), $requestData);

if (!isset($requestData['leads']['add']) &&
    !isset($requestData['leads']['update']) &&
    !isset($requestData['contacts']['add']) &&
    !isset($requestData['contacts']['update']))
    die;

$notifyProcessor = new NotifyProcessor($requestData['account']['subdomain']);
$amoApi = new AmoApi($requestData['account']['subdomain']);

if (isset($requestData['leads']['add'])) {
    $leadId = $requestData['leads']['add'][0]['id'];
    $leadName = $requestData['leads']['add'][0]['name'];
    $leadAddedTimestamp = $requestData['leads']['add'][0]['last_modified'];
    $responsibleUserId = $requestData['leads']['add'][0]['responsible_user_id'];
    $responsibleUser = $amoApi->getUserById($responsibleUserId);

    echo $notifyProcessor->addNotify($leadId, $leadName,'leads', $responsibleUser['name'], $leadAddedTimestamp);
}
elseif (isset($requestData['contacts']['add'])) {
    $contactId = $requestData['contacts']['add'][0]['id'];
    $contactName = $requestData['contacts']['add'][0]['name'];
    $contactAddedTimestamp = $requestData['contacts']['add'][0]['last_modified'];
    $responsibleUserId = $requestData['contacts']['add'][0]['responsible_user_id'];
    $responsibleUser = $amoApi->getUserById($responsibleUserId);

    echo $notifyProcessor->addNotify($contactId, $contactName, 'contacts', $responsibleUser['name'], $contactAddedTimestamp);
}
//elseif (isset($requestData['leads']['update']))