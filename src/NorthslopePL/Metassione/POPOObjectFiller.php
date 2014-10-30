<?php
namespace NorthslopePL\Metassione;

class POPOObjectFiller
{
	public function fillObjectWithRawData($targetObject, $rawData)
	{
		$targetObject->setStringValue($rawData->name);
	}
}
