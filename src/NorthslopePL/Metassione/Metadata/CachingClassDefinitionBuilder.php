<?php
namespace NorthslopePL\Metassione\Metadata;

class CachingClassDefinitionBuilder implements ClassDefinitionBuilderInterface
{
	/**
	 * @var ClassDefinitionBuilderInterface
	 */
	private $classDefinitionBuilder;

	/**
	 * @var ClassDefinition[]
	 */
	private $cache = [];

	public function __construct(ClassDefinitionBuilderInterface $classDefinitionBuilder)
	{
		$this->classDefinitionBuilder = $classDefinitionBuilder;
	}

	public function buildFromClass($classname)
	{
		if (!isset($this->cache[$classname])) {
			$this->cache[$classname] = $this->classDefinitionBuilder->buildFromClass($classname);
		}

		return $this->cache[$classname];
	}


}
