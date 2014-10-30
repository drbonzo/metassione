<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses;

class IncorrectlyDefinedPropertiesKlass
{
	/**
	 * @var void
	 */
	private $voidProperty;

	private $noPhpdocProperty;

	/**
	 * Foo bar but no [AT]var
	 */
	private $noVarInPhpdocProperty;

	/**
	 * but not array type specification
	 *
	 * @var array
	 */
	private $invalidArrayProperty;

}
