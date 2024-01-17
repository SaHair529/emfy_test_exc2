<?php
namespace Amocrm;

use Amocrm\Oauth\OauthProcessor;

class AmoApi
{
    public function __construct(private string $subdomain)
    {
    }

    public function getUserById(int $id): array
    {
        return $this->sendGetRequest("https://$this->subdomain.amocrm.ru/api/v4/users/$id");
    }

    public function addNote(string $entityType, int $entityId, string $noteType, array $params): string|bool
    {
        return $this->sendPostRequest("https://$this->subdomain.amocrm.ru/api/v4/$entityType/notes", [[
            'entity_id' => $entityId,
            'note_type' => $noteType,
            'params' => $params
        ]]);
    }

    private function sendPostRequest(string $url, array $requestData): bool|string
    {
        $accountAccessTokenData = $this->getAccessTokenData();
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
        $responseAr = json_decode($response, true);
        if (isset($responseAr['status']) && $responseAr['status'] === 401) {
            $this->refreshToken();
            $accountAccessTokenData = $this->getAccessTokenData();
            $requestHeaders = [
                'Authorization: Bearer '.$accountAccessTokenData['access_token'],
                'Content-Type: application/json'
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
            $response = curl_exec($ch);
        }

        curl_close($ch);
        return $response;
    }

    private function sendGetRequest(string $url): array
    {
        $accountAccessTokenData = $this->getAccessTokenData();
        $requestHeaders = [
            'Authorization: Bearer '.$accountAccessTokenData['access_token'],
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Включаем возврат результата в переменную
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = json_decode(curl_exec($ch), true);

        if (isset($response['status']) && $response['status'] === 401) {
            $this->refreshToken();
            $accountAccessTokenData = $this->getAccessTokenData();
            $requestHeaders = [
                'Authorization: Bearer '.$accountAccessTokenData['access_token'],
                'Content-Type: application/json'
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
            $response = json_decode(curl_exec($ch), true);
        }

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