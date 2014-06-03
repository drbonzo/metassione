<?php
namespace NorthslopePL\Metassione;

class POPOConverter
{
	/**
	 * @param object $value
	 *
	 * @return \stdClass
	 *
	 * @throws ConversionException
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
			return new \stdClass();
		}
		else
		{
			throw new ConversionException('Given value is not an array nor an object. Type was: ' . gettype($value));
		}
	}
}
