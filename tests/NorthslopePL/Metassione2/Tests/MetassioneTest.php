<?php
namespace NorthslopePL\Metassione2\Tests;

use NorthslopePL\Metassione2\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione2\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione2\Metassione;
use NorthslopePL\Metassione2\POPOConverter;
use NorthslopePL\Metassione2\POPOObjectFiller;
use NorthslopePL\Metassione2\Tests\Fixtures\Klasses\OnePropertyKlass;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;

class MetassioneTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|POPOConverter
	 */
	private $popoConverter;

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|POPOObjectFiller
	 */
	private $popoObjectFiller;

	protected function setUp()
	{
		$this->popoConverter = $this->getMock(POPOConverter::class, ['convert']);
		$this->popoObjectFiller = $this->getMock(POPOObjectFiller::class, ['fillObjectWithRawData'], [new ClassDefinitionBuilder(new ClassPropertyFinder())]);
	}

	public function testConvertingPOPO()
	{
		$metassione = new Metassione($this->popoConverter, $this->popoObjectFiller);

		$sampleObject = new OnePropertyKlass();
		$sampleObject->setValue(123);

		$this->popoConverter->expects($this->once())->method('convert')->with($this->equalTo($sampleObject));

		$metassione->convertToStdClass($sampleObject);
	}

	public function testFillingPOPO()
	{
		$rawData = new stdClass();
		$rawData->value = 123;

		$sampleObject = new OnePropertyKlass();

		$this->popoObjectFiller->expects($this->once())->method('fillObjectWithRawData')->with($this->equalTo($sampleObject))->willReturn($sampleObject);

		$metassione = new Metassione($this->popoConverter, $this->popoObjectFiller);
		$result = $metassione->fillObjectWithRawData($sampleObject, $rawData);

		$this->assertSame($sampleObject, $result);
	}
}
