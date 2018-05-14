<?php

namespace App\Service\Form;

use App\Entity\FormConfig;

/**
 * Class ConfigDecorator
 */
class ConfigDecorator
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
    public function decorate(FormConfig $formConfig): void
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
                    '/[^a-z0-9]+/',
                    '-',
                    strtolower($this->removeLithuanianLetters($label))
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

    private function removeLithuanianLetters(string $label)
    {
        $map = [
            'ą' => 'a',
            'č' => 'c',
            'ę' => 'e',
            'ė' => 'e',
            'į' => 'i',
            'š' => 's',
            'ų' => 'u',
            'ū' => 'u',
            'ž' => 'z',
            'Ą' => 'A',
            'Č' => 'C',
            'Ę' => 'E',
            'Ė' => 'E',
            'Į' => 'I',
            'Š' => 'S',
            'Ų' => 'U',
            'Ū' => 'U',
            'Ž' => 'Z',
        ];

        foreach ($map as $key => $value) {
            $label = str_replace($key, $value, $label);
        }

        return $label;
    }
}
