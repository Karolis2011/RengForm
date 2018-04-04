<?php

namespace App\Service\Export\Parser;

use App\Entity\Category;

/**
 * Class CategoryParser
 */
class CategoryParserInterface implements ParserInterface
{
    /**
     * @param $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array
    {
        if (!($object instanceof Category)) {
            throw new \Exception(sprintf('Category expected, got %s', get_class($object)));
        }

        $data = [
            [
                'Category',
                $object->getTitle(),
            ],
        ];

        if (is_iterable($object->getWorkshops()) && count($object->getWorkshops()) > 0) {
            foreach ($object->getWorkshops() as $workshop) {
                $data = array_merge($data, WorkshopParserInterface::parse($workshop));
            }
        } else {
            $data[] = ['No workshops in category'];
        }

        return $data;
    }
}
