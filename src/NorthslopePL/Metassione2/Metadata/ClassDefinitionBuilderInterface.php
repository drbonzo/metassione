<?php
namespace NorthslopePL\Metassione2\Metadata;

interface ClassDefinitionBuilderInterface
{
	/**
	 * @param string $classname
	 *
	 * @return ClassDefinition
	 */
	public function buildFromClass($classname);
}
