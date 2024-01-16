<?php
namespace Amocrm;

use Amocrm\Oauth\OauthProcessor;

class AmoApi
{
    public function __construct(private string $subdomain)
    {
    }

    public function addNote(string $entityType, int $entityId, string $noteType, array $params)
    {
        $this->sendPostRequest("https://$this->subdomain.amocrm.ru/api/v4/$entityType/$entityId/notes", [
            'note_type' => $noteType,
            'params' => $params
        ]);
    }

    private function sendPostRequest(string $url, array $requestData)
    {
        $accountAccessTokenData = $this->getAccessTokenData();
        if (time()-5 >= $accountAccessTokenData['expires_in']) {
            $this->refreshToken();
            $accountAccessTokenData = $this->getAccessTokenData();
            # todo Дописать логику отправки запросов в амо с использованием access token
        }
    }


    private function getAccessTokenData()
    {
        return json_decode(file_get_contents(ACCESS_TOKEN_DIRPATH."/$this->subdomain.json"));
    }

    private function refreshToken()
    {
        $accountAccessTokenData = OauthProcessor::refreshToken($this->subdomain);
        file_put_contents(ACCESS_TOKEN_DIRPATH."/$this->subdomain.json", $accountAccessTokenData);
    }
}