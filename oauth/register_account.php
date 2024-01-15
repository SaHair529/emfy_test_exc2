<?php
require_once __DIR__.'/../vendor/autoload.php';

if (!isset($_GET['code']) ||
    !isset($_GET['referer']) ||
    !isset($_GET['client_id']) ||
    !isset($_GET['client_secret']))
    die;

$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$accessTokenData = OauthProcessor::registerAccount($_GET['referer'], [
    'client_id' => $_GET['client_id'],
    'client_secret' => $_GET['client_secret'],
    'code' =>$_GET['code'],
    'redirect_uri' => $currentUrl
]);

$subdomain = explode('.', $_GET['referer'])[0];
file_put_contents(ACCESS_TOKEN_DIRPATH."/$subdomain.json", $accessTokenData);
