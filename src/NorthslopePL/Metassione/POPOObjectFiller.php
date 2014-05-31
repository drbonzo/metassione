<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Tests\SampleClass;

class POPOObjectFiller
{
	/**
	 * @param object $targetObject
	 * @param \stdClass $rawData
	 */
	public function fillObjectWithRawData($targetObject, $rawData)
	{
		$targetObject->setName($rawData->name);
	}
}
