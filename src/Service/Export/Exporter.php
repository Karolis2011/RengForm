<?php

namespace App\Service\Export;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\EventTime;
use App\Entity\MultiEvent;
use App\Entity\Workshop;
use App\Entity\WorkshopTime;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Exporter
 */
class Exporter
{
    const FILE_NAME = 'export.csv';
    const PARSERS = [
        MultiEvent::class   => Parser\MultiEventParser::class,
        Category::class     => Parser\CategoryParser::class,
        Workshop::class     => Parser\WorkshopParser::class,
        WorkshopTime::class => Parser\WorkshopTimeParser::class,
        Event::class        => Parser\EventParser::class,
        EventTime::class    => Parser\EventTimeParser::class,
    ];

    /**
     * @param $item
     * @return Response
     * @throws \Exception
     */
    public function export($item): Response
    {
        $data = $this->getParser($item)->parse($item);
        $response = $this->getResponse($this->translateToCsv($data));

        return $response;
    }

    /**
     * @param array $data
     * @return string
     */
    private function translateToCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $data = '';

        while ($line = fgets($output)) {
            $data .= $line;
        }

        $data .= fgets($output);

        return $data;
    }

    /**
     * @param string $data
     * @return Response
     */
    private function getResponse(string $data): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', self::FILE_NAME));
        $response->setContent($data);

        return $response;
    }

    /**
     * @param $item
     * @return Parser\ParserInterface
     * @throws \Exception
     */
    private function getParser($item): Parser\ParserInterface
    {
        $class = get_class($item);

        if (!isset(self::PARSERS[$class])) {
            throw new \Exception(sprintf('Parser for class %s not found', $class));
        }

        $parser = self::PARSERS[$class];
        $parser = new $parser();

        return $parser;
    }
}
