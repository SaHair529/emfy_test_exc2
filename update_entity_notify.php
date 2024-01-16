<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/autoload.php';

parse_str(file_get_contents('php://input'), $requestData);

if (!isset($requestData['leads']['add']) &&
    !isset($requestData['leads']['update']) &&
    !isset($requestData['contacts']['add']) &&
    !isset($requestData['contacts']['update']))
    die;

$notifyProcessor = new NotifyProcessor($requestData['account']['subdomain']);

if (isset($requestData['leads']['add'])) {
    $leadId = $requestData['leads']['add'][0]['id'];
    $leadName = $requestData['leads']['add'][0]['name'];
    $leadAddedTimestamp = $requestData['leads']['add'][0]['last_modified'];
    $responsibleUserId = $requestData['leads']['add'][0]['responsible_user_id'];
    $responsibleUserName = 'TODO Добавить процесс получения имени'; # todo Добавить процесс получения имени

    $notifyProcessor->addNotify($leadId, $leadName,'leads', $responsibleUserName, $leadAddedTimestamp);
}
elseif (isset($requestData['contacts']['add'])) {
    $contactId = $requestData['contacts']['add'][0]['id'];
    $contactName = $requestData['contacts']['add'][0]['name'];
    $contactAddedTimestamp = $requestData['contacts'][0]['add']['last_modified'];
    $responsibleUserId = $requestData['contacts']['add'][0]['responsible_user_id'];
    $responsibleUserName = 'TODO Добавить процесс получения имени'; # todo Добавить процесс получения имени

    $notifyProcessor->addNotify($contactId, $contactName, 'contacts', $responsibleUserName, $contactAddedTimestamp);
}
//elseif (isset($requestData['leads']['update']))