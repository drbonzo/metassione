<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\SimpleKlass;
use NorthslopePL\Metassione\Tests\Fixtures\Filler\ArrayTypedPropertyKlass;
use stdClass;

class POPOObjectFillerArrayPropertiesTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOObjectFiller
	 */
	private $objectFiller;

	protected function setUp()
	{
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()), new PropertyValueCaster());
	}

	public function testFillingKlassWithArrayTypedPropertiesWithFullData()
	{
		$targetObject = new ArrayTypedPropertyKlass();

		$rawData = new stdClass();
		{
			$rawItem_1_1 = new stdClass();
			$rawItem_1_1->name = 'aaa';

			$rawItem_1_2 = new stdClass();
			$rawItem_1_2->name = 'bbb';

			$rawItem_2_1 = new stdClass();
			$rawItem_2_1->name = 'ccc';

			$rawItem_2_2 = new stdClass();
			$rawItem_2_2->name = 'ddd';

			$rawData->objectItemsNotNullable = [
				$rawItem_1_1,
				$rawItem_1_2,
			];
			$rawData->objectItemsNullable = [
				$rawItem_2_1,
				$rawItem_2_2,
			];
		}

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new ArrayTypedPropertyKlass();
		{
			$expectedObject->objectItemsNotNullable = [
				new SimpleKlass('aaa'),
				new SimpleKlass('bbb')
			];

			$expectedObject->objectItemsNullable = [
				new SimpleKlass('ccc'),
				new SimpleKlass('ddd')
			];
		}

		$this->assertEquals($expectedObject, $targetObject);
	}

	public function testFillingKlassWithArrayTypedPropertiesWithNoData()
	{
		$targetObject = new ArrayTypedPropertyKlass();

		$rawData = new stdClass();

		$this->objectFiller->fillObjectWithRawData($targetObject, $rawData);

		$expectedObject = new ArrayTypedPropertyKlass();
		{
			$expectedObject->objectItemsNotNullable = [];
			$expectedObject->objectItemsNullable = []; // it is an array even if it is nullable
		}

		$this->assertEquals($expectedObject, $targetObject);
	}
}
