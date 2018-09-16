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
        'hidden'
    ];

    // Group form constants
    const GROUP_COUNT_FIELD_NAME = '_group_size';
    const GROUP_COUNT_FIELD_DEFAULT_LABEL = 'Group size';
    const GROUP_COUNT_FIELD_TYPE = 'number';

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

            // Clear label
            $label = $this->clearLabel($label);

            // Generate name for a field
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
            $config[$key][self::LABEL] = $label;
        }

        if ($formConfig->getType() == FormConfig::GROUP) {
            $this->addGroupFormField($config);
        }

        $formConfig->setConfig(json_encode($config));
    }

    /**
     * @param string $label
     * @return mixed|string
     */
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

    /**
     * @param array $config
     */
    private function addGroupFormField(array &$config)
    {
        $exists = false;

        foreach ($config as $field) {
            if ($field[self::TYPE] == self::GROUP_COUNT_FIELD_TYPE
                && $field[self::NAME] == self::GROUP_COUNT_FIELD_NAME
            ) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $config[] = [
                self::TYPE  => self::GROUP_COUNT_FIELD_TYPE,
                self::NAME  => self::GROUP_COUNT_FIELD_NAME,
                self::LABEL => self::GROUP_COUNT_FIELD_DEFAULT_LABEL,
                'required'  => true,
            ];
        }
    }

    /**
     * @param string $label
     * @return string
     */
    private function clearLabel(string $label): string
    {
        $label = str_replace('<br>', '', $label);

        return $label;
    }
}
