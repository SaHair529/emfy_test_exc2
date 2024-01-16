<?php
namespace Amocrm\Oauth;

use RequestsSender;

class OauthProcessor
{
    public static function registerAccount(string $referer, array $registerData): string|bool
    {
        $registerData += [
            'grant_type' => 'authorization_code'
        ];

        $amoResponse =  json_decode(RequestsSender::post("https://$referer/oauth2/access_token", $registerData), true);
        if ($amoResponse['status'] !== 200 && $amoResponse['status'] !== 201)
            return false;

        return json_encode($amoResponse);
    }

    public static function refreshToken(string $subdomain)
    {
        $accessTokenData = json_decode(file_get_contents(ACCESS_TOKEN_DIRPATH."/$subdomain.json"));
        $clientSecrets = json_decode(file_get_contents(CLIENT_SECRETS_DIRPATH."/$subdomain.json"));
        $requestData = [
            'client_id' => $clientSecrets['client_id'],
            'client_secret' => $clientSecrets['client_secret'],
            'grant_type' => 'refresh_token',
            'refresh_token' => $accessTokenData['refresh_token'],
            'redirect_uri' => $clientSecrets['redirect_uri']
        ];

        return RequestsSender::post("https://$subdomain.amocrm.ru/oauth2/refresh_token", $requestData);
    }
}