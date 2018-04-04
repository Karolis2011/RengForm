<?php

namespace App\Service\Export\Parser;

use App\Entity\Event;

/**
 * Class EventParser
 */
class EventParserInterface implements ParserInterface
{
    /**
     * @param $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array
    {
        if (!($object instanceof Event)) {
            throw new \Exception(sprintf('Event expected, got %s', get_class($object)));
        }

        $data = [];

        if (is_iterable($object->getTimes()) && count($object->getTimes()) > 0) {
            foreach ($object->getTimes() as $workshopTime) {
                $data[] = [];
                $data = array_merge($data, EventTimeParserInterface::parse($workshopTime));
            }
        } else {
            $data[] = ['No times set for event'];
        }

        return $data;
    }
}
