<?php

namespace App\Service\Export\Parser;

use App\Entity\MultiEvent;

/**
 * Class MultiEventParser
 */
class MultiEventParser implements ParserInterface
{
    /**
     * @param MultiEvent $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array
    {
        if (!($object instanceof MultiEvent)) {
            throw new \Exception(sprintf('MultiEvent expected, got %s', get_class($object)));
        }

        $data = [
            [
                'MultiEvent',
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
