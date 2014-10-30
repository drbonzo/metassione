<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\MetadataHelper;

class Metassione
{
	/**
	 * @param object|array $object POJO
	 * @return \stdClass
	 */
	public function convertToStdClass($object)
	{
		$converter = new POPOConverter();
		return $converter->convert($object);
	}

	/**
	 * @param object $targetObject POJO
	 * @param \stdClass $rawData
	 *
	 * @param ReflectionCache $reflectionCache
	 */
	public function fillObjectWithRawData($targetObject, \stdClass $rawData, ReflectionCache $reflectionCache = null)
	{
		if ($reflectionCache === null)
		{
			$metadataHelper = new MetadataHelper();
			$reflectionCache = new InMemoryReflectionCache($metadataHelper);
		}

		$filler = new POPOObjectFiller($reflectionCache);
		$filler->fillObjectWithRawData($targetObject, $rawData);
	}
}
