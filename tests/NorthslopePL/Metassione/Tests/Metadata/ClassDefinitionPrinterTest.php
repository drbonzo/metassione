<?php
namespace NorthslopePL\Metassione\Tests\Metadata;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassDefinitionPrinter;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\ArrayPropertiesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\ArrayPropertiesNullableKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\BasicTypesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\BasicTypesNullableKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\ClassTypesTypeNullablePropertiesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\ClassTypesTypePropertiesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\UndefinedTypeKlass;
use PHPUnit_Framework_TestCase;

class ClassDefinitionPrinterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var ClassDefinitionPrinter
	 */
	private $classDefinitionPrinter;

	/**
	 * @var ClassDefinitionBuilder
	 */
	private $classDefinitionBuilder;

	protected function setUp()
	{
		$this->classDefinitionPrinter = new ClassDefinitionPrinter();
		$this->classDefinitionBuilder = new ClassDefinitionBuilder(new ClassPropertyFinder());
	}

	/**
	 * @param $className
	 * @param $filename
	 *
	 * @dataProvider printingDataProvider
	 */
	public function testPrinting($className, $filename)
	{
		$classDefinition = $this->classDefinitionBuilder->buildFromClass($className);

		$actual = $this->classDefinitionPrinter->buildDescription($classDefinition);

		$this->assertStringEqualsFile(__DIR__ . '/../Fixtures/Printer/' . $filename, $actual);
	}

	public function printingDataProvider()
	{
		return [
			[BasicTypesKlass::class, 'BasicTypesKlass.md'],
			[BasicTypesNullableKlass::class, 'BasicTypesNullableKlass.md'],
			[ClassTypesTypePropertiesKlass::class, 'ClassTypesTypePropertiesKlass.md'],
			[ClassTypesTypeNullablePropertiesKlass::class, 'ClassTypesTypeNullablePropertiesKlass.md'],
			[ArrayPropertiesKlass::class, 'ArrayPropertiesKlass.md'],
			[ArrayPropertiesNullableKlass::class, 'ArrayPropertiesNullableKlass.md'],
			[UndefinedTypeKlass::class, 'UndefinedTypeKlass.md'],
		];
	}
}
