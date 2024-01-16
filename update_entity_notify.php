<?php

require_once __DIR__.'/vendor/autoload.php';

if (!isset($_POST['leads']['add']) &&
    !isset($_POST['leads']['update']) &&
    !isset($_POST['contacts']['add']) &&
    !isset($_POST['contacts']['update']))
    die;



if (isset($_POST['leads']['add'])) {
    $leadId = $_POST['leads']['add']['id'];
    $leadName = $_POST['leads']['add']['name'];
    $leadAddedTimestamp = $_POST['leads']['add']['last_modified'];
    $responsibleUserId = $_POST['leads']['add']['responsible_user_id'];
    $responsibleUserName = 'TODO Добавить процесс получения имени'; # todo Добавить процесс получения имени

    $notifyProcessor = new NotifyProcessor($_POST['account']['subdomain']);
    $notifyProcessor->addNotify($leadId, $leadName,'leads', $responsibleUserName, $leadAddedTimestamp);
}
//elseif (isset($_POST['leads']['update']))
//    NotifyProcessor::updateNotify();