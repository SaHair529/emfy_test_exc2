<?php
namespace Amocrm;

use Amocrm\Oauth\OauthProcessor;

class AmoApi
{
    public function __construct(private string $subdomain)
    {
    }

    public function addNote(string $entityType, int $entityId, string $noteType, array $params): array
    {
        return json_decode($this->sendPostRequest("https://$this->subdomain.amocrm.ru/api/v4/$entityType/notes", [[
            'entity_id' => $entityId,
            'note_type' => $noteType,
            'params' => $params
        ]]), true);
    }

    private function sendPostRequest(string $url, array $requestData): bool|string
    {
        $accountAccessTokenData = $this->getAccessTokenData();
        if (time()-5 >= $accountAccessTokenData['expires_in']) {
            $this->refreshToken();
            $accountAccessTokenData = $this->getAccessTokenData();
        }

        $requestHeaders = [
            'Authorization: Bearer '.$accountAccessTokenData['access_token'],
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    private function getAccessTokenData()
    {
        return json_decode(file_get_contents(ACCESS_TOKEN_DIRPATH."/$this->subdomain.json"), true);
    }

    private function refreshToken()
    {
        $accountAccessTokenData = OauthProcessor::refreshToken($this->subdomain);
        file_put_contents(ACCESS_TOKEN_DIRPATH."/$this->subdomain.json", $accountAccessTokenData);
    }
}