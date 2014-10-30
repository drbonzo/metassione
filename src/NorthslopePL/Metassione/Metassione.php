<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\ClassStructure\ClassStructureProvider;
use NorthslopePL\Metassione\ClassStructure\SimpleClassStructureProvider;
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
	 * @throws ObjectFillingException
	 */
	public function fillObjectWithRawData($targetObject, \stdClass $rawData)
	{
		$metadataHelper = new MetadataHelper();
		$reflectionCache = new ReflectionCache($metadataHelper);
		$filler = new POPOObjectFiller($reflectionCache);
		$filler->fillObjectWithRawData($targetObject, $rawData);
	}
}
