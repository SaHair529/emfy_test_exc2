<?php

use Amocrm\AmoApi;
use JetBrains\PhpStorm\Pure;

/**
 * Класс создает примечания, оповещающие о создании/изменении сущностей
 */
class NotifyProcessor
{
    private AmoApi $amoApi;

    #[Pure]
    public function __construct(string $subdomain)
    {
        $this->amoApi = new AmoApi($subdomain);
    }

    public function addNotify(int $entityId, string $entityName, string $entityType, string $responsibleUserName, int $entityAddedTimestamp): string|bool
    {
        $entityAddedTimeStr = date('d.m.Y H:i', $entityAddedTimestamp);
        $noteText = "$entityName\nОтветственный: $responsibleUserName\nВремя добавления: $entityAddedTimeStr";

        return $this->amoApi->addNote($entityType, $entityId, 'common', [
            'text' => $noteText
        ]);
    }

    public function updateNotify()
    {

    }
}