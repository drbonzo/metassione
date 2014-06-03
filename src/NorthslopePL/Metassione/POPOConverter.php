<?php
namespace NorthslopePL\Metassione;

class POPOConverter
{
	/**
	 * @param object|array $value
	 *
	 * @return \stdClass
	 */
	public function convert($value)
	{
		if (is_array(($value)))
		{
			$retval = [];
			foreach ($value as $object)
			{
				$retval[] = $this->convert($object); // force sequential indexing
			}

			return $retval;
		}
		else if (is_object($value))
		{
			return $this->convertObject($value);
		}
		else
		{
			return $value;
		}
	}

	private function convertObject($object)
	{
		$retvalObject = new \stdClass();

		$reflectionObject = new \ReflectionObject($object);

		foreach ($reflectionObject->getProperties() as $property)
		{
			$property->setAccessible(true);
			$propertyName = $property->getName();
			$propertyValue = $property->getValue($object);

			if (is_object($propertyValue))
			{
				$propertyValue = $this->convert($propertyValue);
			}
			else if (is_array($propertyValue))
			{
				$propertyValue = $this->convert($propertyValue);
			}
			else
			{
				// just use $propertyValue unchanged
			}

			$retvalObject->$propertyName = $propertyValue;
		}

		return $retvalObject;
	}
}
