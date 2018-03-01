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
        'header',
        'paragraph',
    ];

    /**
     * @param FormConfig $formConfig
     * @return FormConfig
     */
    public function enrich(FormConfig $formConfig): FormConfig
    {
        $config = $formConfig->getConfig();
        $names = [];

        foreach ($config as $key => $field) {
            if (in_array($field[self::TYPE], self::TYPE_BLACKLIST)) {
                continue;
            }

            $name = $field[self::NAME] ?? null;
            $label = $field[self::LABEL];

            if ($name === null) {
                $rawName = sprintf(
                    'registration[%s]',
                    preg_replace(
                        '/[^a-zA-Z0-9]+/',
                        '-',
                        strtolower($label)
                    )
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

        $formConfig->setConfig($config);

        return $formConfig;
    }
}