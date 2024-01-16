<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once __DIR__.'/vendor/autoload.php';

parse_str(file_get_contents('php://input'), $requestData);

if (!isset($requestData['leads']['add']) &&
    !isset($requestData['leads']['update']) &&
    !isset($requestData['contacts']['add']) &&
    !isset($requestData['contacts']['update']))
    die;

$notifyProcessor = new NotifyProcessor($requestData['account']['subdomain']);

if (isset($requestData['leads']['add'])) {
    $leadId = $requestData['leads']['add']['id'];
    $leadName = $requestData['leads']['add']['name'];
    $leadAddedTimestamp = $requestData['leads']['add']['last_modified'];
    $responsibleUserId = $requestData['leads']['add']['responsible_user_id'];
    $responsibleUserName = 'TODO Добавить процесс получения имени'; # todo Добавить процесс получения имени

    $notifyProcessor->addNotify($leadId, $leadName,'leads', $responsibleUserName, $leadAddedTimestamp);
}
elseif (isset($requestData['contacts']['add'])) {
    $contactId = $requestData['contacts']['add']['id'];
    $contactName = $requestData['contacts']['add']['name'];
    $contactAddedTimestamp = $requestData['contacts']['add']['last_modified'];
    $responsibleUserId = $requestData['contacts']['add']['responsible_user_id'];
    $responsibleUserName = 'TODO Добавить процесс получения имени'; # todo Добавить процесс получения имени

    $notifyProcessor->addNotify($contactId, $contactName, 'contacts', $responsibleUserName, $contactAddedTimestamp);
}
//elseif (isset($requestData['leads']['update']))