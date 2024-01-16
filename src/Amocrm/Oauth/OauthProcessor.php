<?php
namespace Amocrm\Oauth;

use RequestsSender;

class OauthProcessor
{
    public static function registerAccount(string $referer, array $registerData): array
    {
        $registerData += [
            'grant_type' => 'authorization_code'
        ];

        return  json_decode(RequestsSender::post("https://$referer/oauth2/access_token", $registerData), true);
    }

    public static function refreshToken(string $subdomain)
    {
        $accessTokenData = json_decode(file_get_contents(ACCESS_TOKEN_DIRPATH."/$subdomain.json"), true);
        $clientSecrets = json_decode(file_get_contents(CLIENT_SECRETS_DIRPATH."/$subdomain.json"), true);
        $requestData = [
            'client_id' => $clientSecrets['client_id'],
            'client_secret' => $clientSecrets['client_secret'],
            'grant_type' => 'refresh_token',
            'refresh_token' => $accessTokenData['refresh_token'],
            'redirect_uri' => $clientSecrets['redirect_uri']
        ];

        return RequestsSender::post("https://$subdomain.amocrm.ru/oauth2/access_token", $requestData);
    }
}