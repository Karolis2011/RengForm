<?php

namespace App\Service\Export\Parser;

use App\Entity\Event;

/**
 * Class EventParser
 */
class EventParser implements Parser
{
    /**
     * @param Event $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array
    {
        if (!($object instanceof Event)) {
            throw new \Exception(sprintf('Event expected, got %s', get_class($object)));
        }

        $data = [
            [
                'Event',
                $object->getTitle(),
            ],
        ];

        foreach ($object->getCategories() as $category) {
            $data[] = [];
            $data = array_merge($data, CategoryParser::parse($category));
        }

        return $data;
    }
}
