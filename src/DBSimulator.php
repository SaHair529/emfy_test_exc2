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

    public static function getChangedFields(array $updatedEntity, string $entityType)
    {
        $changedFields = [];
        $oldEntity = self::getEntity($updatedEntity['id'], $entityType);

        if ($oldEntity['name'] !== $updatedEntity['name'])
            $changedFields['Имя'] = $updatedEntity['name'];
        if ($entityType === 'leads' && $oldEntity['price'] !== $updatedEntity['price'])
            $changedFields['Бюджет'] = $updatedEntity['price'];

        $updatedCustomFields = array_column($updatedEntity['custom_fields'] ?? [], null, 'id');
        $oldCustomFields = array_column($oldEntity['custom_fields'] ?? [], null, 'id');

        foreach ($updatedCustomFields as $fieldId => $field) {
            if (!isset($oldCustomFields[$fieldId])) {
                $changedFields[$field['name']] = $field['values'][0]['value'];
                continue;
            }

            if ($oldCustomFields[$fieldId]['values'][0]['value'] !== $field['values'][0]['value'])
                $changedFields[$field['name']] = $field['values'][0]['value'];
        }

        foreach ($oldCustomFields as $fieldId => $field) {
            if (!isset($updatedCustomFields[$fieldId]))
                $changedFields[$field['name']] = 'Поле очищено';
        }

        return $changedFields;
    }
}