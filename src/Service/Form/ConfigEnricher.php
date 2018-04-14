<?php

namespace App\Service\Form;

use App\Entity\FormConfig;

/**
 * Class ConfigEnricher
 */
class ConfigEnricher
{
    const TYPE = 'type';
    const LABEL = 'label';
    const NAME = 'name';
    const TYPE_BLACKLIST = [
        'paragraph',
    ];

    /**
     * @param FormConfig $formConfig
     */
    public function enrich(FormConfig $formConfig): void
    {
        $config = $formConfig->getConfigParsed();
        $names = [];

        foreach ($config as $key => $field) {
            if (in_array($field[self::TYPE], self::TYPE_BLACKLIST)) {
                continue;
            }

            $name = $field[self::NAME] ?? null;
            $label = $field[self::LABEL];

            if ($name === null) {
                $rawName = preg_replace(
                    '/[^a-zA-Z0-9]+/',
                    '-',
                    strtolower($label)
                );
                $name = $rawName;
                $i = 1;

                while (isset($names[$name])) {
                    $name = $rawName . '-' . $i;
                    $i++;
                }
            }

            $names[$name] = $label;
            $config[$key][self::NAME] = $name;
        }

        $formConfig->setConfig(json_encode($config));
    }
}
