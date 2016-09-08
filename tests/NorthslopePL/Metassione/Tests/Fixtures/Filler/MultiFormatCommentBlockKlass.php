<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Filler;

use NorthslopePL\Metassione\Tests\Fixtures\Builder\SubNamespace\OtherSimpleKlass;

class MultiFormatCommentBlockKlass
{
	/**
     * some text
	 * @var SimpleKlass[]    //
     * other text
     * @return OtherSimpleKlass
	 */
	public $objectItemsNotNullable = [];

	/** @var SimpleKlass[]|null*/
	public $objectItemsNullable = [];

	/** @var string[] */
	public $stringItemsNotNullable = [];

	/**     @var      string[]|null     //  	 */
	public $stringItemsNullable = [];
}
