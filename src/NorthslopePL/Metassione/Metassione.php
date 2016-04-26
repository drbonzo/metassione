<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use stdClass;

class Metassione
{
	/**
	 * @var POPOConverter
	 */
	private $converter;

	/**
	 * @var POPOObjectFiller
	 */
	private $filler;

	/**
	 * Metassione constructor.
	 *
	 * @param POPOConverter|null $converter
	 * @param POPOObjectFiller|null $filler
	 */
	public function __construct(POPOConverter $converter = null, POPOObjectFiller $filler = null)
	{
		$this->converter = $converter ? $converter : new POPOConverter();
		$this->filler = $filler ? $filler : new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	/**
	 * @param object|array $object POJO
	 * @return stdClass
	 */
	public function convertToStdClass($object)
	{
		return $this->converter->convert($object);
	}

	/**
	 * @param object $targetObject POJO
	 * @param stdClass $rawData
	 *
	 * @return object the same as $targetObject
	 */
	public function fillObjectWithRawData($targetObject, stdClass $rawData)
	{
		$this->filler->fillObjectWithRawData($targetObject, $rawData);

		return $targetObject;
	}
}
