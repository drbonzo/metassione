<?php
namespace NorthslopePL\Metassione\Metadata;

interface ClassDefinitionBuilderInterface
{
	/**
	 * @param string $classname
	 *
	 * @return ClassDefinition
	 */
	public function buildFromClass($classname);
}
