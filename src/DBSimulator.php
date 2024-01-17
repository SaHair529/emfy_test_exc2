<?php

class DBSimulator
{
    /**
     * @param string $entityType leads|contacts
     * @param array $entity
     */
    public static function saveEntity(string $entityType, array $entity)
    {
        $entityFilepath = ENTITIES_DIRPATH."/$entityType/{$entity['id']}.json";
        file_put_contents($entityFilepath, json_encode($entity, JSON_UNESCAPED_UNICODE));
    }

    public static function getEntity(int $id, string $entityType): array
    {
        $entityFilepath = ENTITIES_DIRPATH."/$entityType/$id.json";
        return json_decode(file_get_contents($entityFilepath), true);
    }
}