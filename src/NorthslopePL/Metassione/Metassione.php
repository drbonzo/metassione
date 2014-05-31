<?php
namespace NorthslopePL\Metassione;

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
	 */
	public function fillObjectWithRawData($targetObject, \stdClass $rawData)
	{
		$filler = new POPOObjectFiller();
		$filler->fillObjectWithRawData($targetObject, $rawData);
	}
}
