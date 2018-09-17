<?php

namespace App\Service\Form\Validator;

use App\Service\Form\FormField;

/**
 * Class EmailField
 */
class EmailField implements ValidatorInterface
{
    const TYPE = 'email';
    const PATTERN_LOOSE = '/^.+\@\S+\.\S+$/';
    const MESSAGE = 'This value is not a valid email address.';

    /**
     * @param FormField $field
     * @param array     $fieldData
     * @return array
     */
    public function validate(FormField $field, array $fieldData): array
    {
        $value = $fieldData[$field->getName()] ?? null;

        if (null === $value || '' === $value) {
            return [];
        }

        $value = (string)$value;
        if (!preg_match(self::PATTERN_LOOSE, $value)) {
            return [self::MESSAGE];
        }

        $host = (string)substr($value, strrpos($value, '@') + 1);

        // Check for host DNS resource records
        if (!$this->checkHost($host)) {
            return [self::MESSAGE];
        }

        return [];
    }

    /**
     * Check DNS Records for MX type.
     * @param string $host
     * @return bool
     */
    private function checkMX(string $host): bool
    {
        return '' !== $host && checkdnsrr($host, 'MX');
    }

    /**
     * Check if one of MX, A or AAAA DNS RR exists.
     * @param string $host
     * @return bool
     */
    private function checkHost(string $host): bool
    {
        return '' !== $host && ($this->checkMX($host) || (checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA')));
    }
}
