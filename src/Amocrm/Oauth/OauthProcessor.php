<?php
namespace Amocrm\Oauth;

class OauthProcessor
{
    public static function registerAccount(string $referer, array $registerData): string
    {
        $registerData += [
            'grant_type' => 'authorization_code'
        ];

        return RequestsSender::post("https://$referer/oauth2/access_token", $registerData);
    }
}