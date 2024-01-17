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
    $lead = $requestData['leads']['add'][0];
    DBSimulator::saveEntity('leads', $lead);

    $responsibleUser = $amoApi->getUserById($lead['responsible_user_id']);

    echo $notifyProcessor->addNotify($lead['id'], $lead['name'],'leads', $responsibleUser['name'], $lead['last_modified']);
}
elseif (isset($requestData['contacts']['add'])) {
    $contact = $requestData['contacts']['add'][0];
    DBSimulator::saveEntity('contacts', $contact);

    $responsibleUser = $amoApi->getUserById($contact['responsible_user_id']);

    echo $notifyProcessor->addNotify($contact['id'], $contact['name'], 'contacts', $responsibleUser['name'], $contact['last_modified']);
}
elseif (isset($requestData['leads']['update'])) {
    $leadId = $requestData['leads']['update'][0]['id'];
    $leadUpdatedTimestamp = $requestData['leads']['update'][0]['last_modified'];
    # todo добавить название и значение измененных полей
    echo $notifyProcessor->updateNotify($leadId, 'leads', $leadUpdatedTimestamp);
}
elseif (isset($requestData['contacts']['update'])) {
    $contactId = $requestData['contacts']['update'][0]['id'];
    $contactUpdatedTimestamp = $requestData['contacts']['update'][0]['last_modified'];
    # todo добавить название и значение измененных полей
    echo $notifyProcessor->updateNotify($contactId, 'contacts', $contactUpdatedTimestamp);
}