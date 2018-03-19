<?php

namespace App\Service\Export\Parser;

use App\Entity\Registration;
use App\Entity\Workshop;

/**
 * Class WorkshopParser
 */
class WorkshopParser implements Parser
{
    /**
     * @param $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array
    {
        if (!($object instanceof Workshop)) {
            throw new \Exception(sprintf('Workshop expected, got %s', get_class($object)));
        }

        $fieldList = [];

        foreach ($object->getFormConfig()->getConfig() as $field) {
            if ($field['type'] != 'paragraph') {
                $fieldList[$field['name']] = $field['label'];
            }
        }

        $data = [
            [
                'Workshop',
                $object->getTitle(),
            ],
            ['Registration Date'] + array_values($fieldList),
        ];

        /** @var Registration $registration */
        foreach ($object->getRegistrations() as $registration) {
            $row = [
                $registration->getCreated()->format('Y-m-d H:i:s'),
            ];

            $rawData = $registration->getData();
            foreach (array_keys($fieldList) as $field) {
                if (is_array($rawData[$field])) {
                    $row[] = join(', ', $rawData[$field]);
                } else {
                    $row[] = $rawData[$field];
                }
            }

            $data[] = $row;
        }

        return $data;
    }
}