<?php

namespace App\Service\Export\Parser;

/**
 * Interface Parser
 */
interface ParserInterface
{
    /**
     * @param $object
     * @return array
     * @throws \Exception
     */
    public static function parse($object): array;
}
