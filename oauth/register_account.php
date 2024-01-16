<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../autoload.php';

use Amocrm\Oauth\OauthProcessor;


if (!isset($_GET['code']) ||
    !isset($_GET['referer']) ||
    !isset($_GET['client_id']) ||
    !isset($_GET['client_secret']))
    die;

$redirectUri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'].'?client_secret='.$_GET['client_secret'];

$accessTokenData = OauthProcessor::registerAccount($_GET['referer'], [
    'client_id' => $_GET['client_id'],
    'client_secret' => $_GET['client_secret'],
    'code' =>$_GET['code'],
    'redirect_uri' => $redirectUri
]);

if ($accessTokenData === false)
    die;

$subdomain = explode('.', $_GET['referer'])[0];
file_put_contents(ACCESS_TOKEN_DIRPATH."/$subdomain.json", $accessTokenData);
file_put_contents(CLIENT_SECRETS_DIRPATH."/$subdomain.json", json_encode([
    'client_id' => $_GET['client_id'],
    'client_secret' => $_GET['client_secret'],
    'redirect_uri' => $redirectUri
]));
