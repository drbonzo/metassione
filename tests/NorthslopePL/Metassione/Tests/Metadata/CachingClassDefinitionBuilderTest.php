<?php
namespace NorthslopePL\Metassione\Tests\Metadata;

use NorthslopePL\Metassione\Metadata\CachingClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassDefinition;
use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilderInterface;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\Metadata\PropertyDefinition;
use NorthslopePL\Metassione\Tests\Fixtures\Builder\BasicTypesKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Klasses\EmptyKlass;
use PHPUnit_Framework_TestCase;
use ReflectionProperty;

class CachingClassDefinitionBuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var CachingClassDefinitionBuilder
	 */
	private $builder;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ClassDefinitionBuilderInterface
	 */
	private $internalBuilder;

	protected function setUp()
	{
		$this->internalBuilder = $this->getMock(ClassDefinitionBuilder::class, ['buildFromClass'], [new ClassPropertyFinder()]);
		$this->builder = new CachingClassDefinitionBuilder($this->internalBuilder);
	}

	public function testCachingBuilderCallsInternalBuilder()
	{
		$classname = BasicTypesKlass::class;
		$classDefinition = new ClassDefinition();
		$this->internalBuilder->expects($this->once())->method('buildFromClass')->with($this->equalTo($classname))->willReturn($classDefinition);

		$this->builder->buildFromClass($classname);
	}

	public function testCachingBuilderCallsInternalBuilderOnlyOnceForSameClass()
	{
		$classname = BasicTypesKlass::class;
		$classDefinition = new ClassDefinition();
		$this->internalBuilder->expects($this->once())->method('buildFromClass')->with($this->equalTo($classname))->willReturn($classDefinition);

		$this->builder->buildFromClass($classname);
		$this->builder->buildFromClass($classname); // from cache
		$this->builder->buildFromClass($classname); // from cache
		$this->builder->buildFromClass($classname); // from cache
		$this->builder->buildFromClass($classname); // from cache
	}
}
