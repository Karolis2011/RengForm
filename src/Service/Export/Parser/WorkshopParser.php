<?php

namespace App\Service\Export\Parser;

use App\Entity\Workshop;

/**
 * Class CategoryParser
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

        $data = [];

        if (!empty($object->getTimes())) {
            foreach ($object->getTimes() as $workshopTime) {
                $data[] = [];
                $data = array_merge($data, WorkshopTimeParser::parse($workshopTime));
            }
        } else {
            $data[] = ['No times set for workshop'];
        }

        return $data;
    }
}
