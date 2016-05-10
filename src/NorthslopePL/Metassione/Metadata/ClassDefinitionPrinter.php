<?php
namespace NorthslopePL\Metassione\Metadata;

class ClassDefinitionPrinter
{
	public function buildDescription(ClassDefinition $classDefinition)
	{
		$lines = [];

		$lines[] = sprintf('# %s', $classDefinition->name);
		$lines[] = '';

		if ($classDefinition->properties) {
			$lines[] = '| name | type | defined? | object? | array? | nullable |';
			$lines[] = '| ---- | :--- | :------: | :-----: | :----: | :------: |';

			foreach ($classDefinition->properties as $propertyDefinition) {
				$lines[] = sprintf(
					'| %s | %s | %s | %s | %s | %s |',
					$propertyDefinition->getName(),
					$this->splitTypeInLines($propertyDefinition->getType()),
					$this->boolString($propertyDefinition->getIsDefined()),
					$this->boolString($propertyDefinition->getIsObject()),
					$this->boolString($propertyDefinition->getIsArray()),
					$this->boolString($propertyDefinition->getIsNullable())
				);
			}
		}
		
		return join("\n", $lines) . "\n";
	}

	private function boolString($value)
	{
		return ($value ? '**YES**' : '-');
	}

	private function splitTypeInLines($type)
	{
		return str_replace('\\', '\\', $type);
	}
}
