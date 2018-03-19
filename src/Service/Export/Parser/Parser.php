<?php

namespace App\Service\Export\Parser;

/**
 * Interface Parser
 */
interface Parser
{
    /**
     * @param $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array;
}
