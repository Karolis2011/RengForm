<?php

namespace App\Service\Export\Parser;

use App\Entity\Category;

/**
 * Class CategoryParser
 */
class CategoryParser implements Parser
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

        if (!empty($object->getWorkshops())) {
            foreach ($object->getWorkshops() as $workshop) {
                $data[] = [];
                $data = array_merge($data, WorkshopParser::parse($workshop));
            }
        } else {
            $data[] = ['No workshops in category'];
        }

        return $data;
    }
}
