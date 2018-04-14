<?php

namespace App\Tests\TestCases;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\Traits\ValidatorExtensionTrait;

/**
 * Class TypeDatabaseTestCase
 */
abstract class TypeDatabaseTestCase extends DatabaseTestCase
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    protected function setUp()
    {
        parent::setUp();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtensions($this->getTypeExtensions())
            ->addTypes($this->getTypes())
            ->addTypeGuessers($this->getTypeGuessers())
            ->getFormFactory();

        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (in_array(ValidatorExtensionTrait::class, class_uses($this))) {
            $this->validator = null;
        }
    }

    protected function getExtensions()
    {
        $extensions = [];

        if (in_array(ValidatorExtensionTrait::class, class_uses($this))) {
            $extensions[] = $this->getValidatorExtension();
        }

        return $extensions;
    }

    public static function assertDateTimeEquals(\DateTime $expected, \DateTime $actual)
    {
        self::assertEquals($expected->format('c'), $actual->format('c'));
    }

    public static function assertDateIntervalEquals(\DateInterval $expected, \DateInterval $actual)
    {
        self::assertEquals($expected->format('%RP%yY%mM%dDT%hH%iM%sS'), $actual->format('%RP%yY%mM%dDT%hH%iM%sS'));
    }

    protected function getTypeExtensions()
    {
        return [];
    }

    protected function getTypes()
    {
        return [];
    }

    protected function getTypeGuessers()
    {
        return [];
    }
}
