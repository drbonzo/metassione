<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\TwoBasicPropertyKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\TwoObjectPropertyKlass;
use stdClass;

class POPOObjectFillerObjectSimpleExamplesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()));
	}

	public function testFillingKlassWithPropertiesWithFullData()
	{
		$targetObject = new TwoObjectPropertyKlass();

		$rawData = new stdClass();
		{
			$rawTwoNotNull = new stdClass();
			$rawTwoNotNull->foo1 = 'aaa';
			$rawTwoNotNull->foo2 = 'bbb';
			$rawData->twoNotNull = $rawTwoNotNull;

			$rawTwoNullable = new stdClass();
			$rawTwoNullable->foo1 = 'ccc';
			$rawTwoNullable->foo2 = 'ddd';
			$rawData->twoNullable = $rawTwoNullable;
		}

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new TwoObjectPropertyKlass();
		{
			$expectedTwoNotNull = new TwoBasicPropertyKlass();
			$expectedTwoNotNull->foo1 = 'aaa';
			$expectedTwoNotNull->foo2 = 'bbb';

			$expectedTwoNullable = new TwoBasicPropertyKlass();
			$expectedTwoNullable->foo1 = 'ccc';
			$expectedTwoNullable->foo2 = 'ddd';

			$expectedObject->twoNotNull = $expectedTwoNotNull;
			$expectedObject->twoNullable = $expectedTwoNullable;
		}
		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingKlassWithPropertiesWithNoData()
	{
		$targetObject = new TwoObjectPropertyKlass();

		$rawData = new stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new TwoObjectPropertyKlass();
		{
			$expectedObject->twoNotNull = new TwoBasicPropertyKlass();
			$expectedObject->twoNullable = null;
		}
		$this->assertEquals($expectedObject, $targetObject, 'all properties have default values');
	}
}
