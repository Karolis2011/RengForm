<?php

namespace App\Tests\Unit\Service\Form;

use App\Service\Form\FormField;
use PHPUnit\Framework\TestCase;

/**
 * Class FormFieldTest
 */
class FormFieldTest extends TestCase
{
    public function testConstruct()
    {
        $field = new FormField([
            'type'        => 'a',
            'label'       => 'b',
            'name'        => 'c',
            'values'      => [
                [
                    'label'    => 'g',
                    'value'    => 'h',
                    'selected' => true,
                ],
            ],
            'value'       => 'd',
            'required'    => true,
            'description' => 'e',
            'placeholder' => 'f',
            'maxlength'   => 1,
            'min'         => 2,
            'max'         => 3,
            'toggle'      => true,
            'multiple'    => true,
        ]);

        $this->assertSame('a', $field->getType());
        $this->assertSame('b', $field->getLabel());
        $this->assertSame('c', $field->getName());
        $this->assertSame('d', $field->getValue());
        $this->assertSame('e', $field->getDescription());
        $this->assertSame('f', $field->getPlaceholder());
        $this->assertSame(1, $field->getMaxLength());
        $this->assertSame(2, $field->getMin());
        $this->assertSame(3, $field->getMax());
        $this->assertTrue($field->isRequired());
        $this->assertTrue($field->isToggle());
        $this->assertTrue($field->isMultiple());
        $values = $field->getValues();
        $this->assertCount(1, $values);
        $value = $values[0];
        $this->assertSame('g', $value->getLabel());
        $this->assertSame('h', $value->getValue());
        $this->assertTrue($value->isSelected());
    }
}
