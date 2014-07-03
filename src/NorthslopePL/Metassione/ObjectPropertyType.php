<?php
namespace NorthslopePL\Metassione;

class ObjectPropertyType
{
	const GENERAL_TYPE_OBJECT = 'OBJECT';
	const GENERAL_TYPE_ARRAY_OF_OBJECTS = 'ARRAY_OF_OBJECTS';
	const GENERAL_TYPE_SIMPLE_TYPE = 'SIMPLE_TYPE';
	const GENERAL_TYPE_ARRAY_OF_SIMPLE_TYPES = 'ARRAY_OF_SIMPLE_TYPES';
	const GENERAL_TYPE_UNKNOWN = 'UNKNOWN';

	const DATA_TYPE_NULL = 'NULL';

	/**
	 * object, array of simple types, array of objects, simple
	 * @var
	 */
	private $generalType;

	/**
	 * classname, basic type for simple types
	 * @var
	 */
	private $dataType;

	/**
	 * @param string $generalType
	 * @param string $dataType
	 */
	public function __construct($generalType, $dataType = null)
	{
		$this->generalType = $generalType;

		$this->dataType = ($dataType ? $dataType : self::DATA_TYPE_NULL);
	}

	/**
	 * @param mixed $dataType
	 */
	public function setDataType($dataType)
	{
		$this->dataType = $dataType;
	}

	/**
	 * @return mixed
	 */
	public function getDataType()
	{
		return $this->dataType;
	}

	/**
	 * @param mixed $generalType
	 */
	public function setGeneralType($generalType)
	{
		$this->generalType = $generalType;
	}

	/**
	 * @return mixed
	 */
	public function getGeneralType()
	{
		return $this->generalType;
	}

}
