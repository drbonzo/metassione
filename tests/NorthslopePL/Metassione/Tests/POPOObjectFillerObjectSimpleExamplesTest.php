<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\Metadata\ClassDefinitionBuilder;
use NorthslopePL\Metassione\Metadata\ClassPropertyFinder;
use NorthslopePL\Metassione\POPOObjectFiller;
use NorthslopePL\Metassione\PropertyValueCaster;
use NorthslopePL\Metassione\Tests\Fixtures\Blog\Author;
use NorthslopePL\Metassione\Tests\Fixtures\Blog\Blog;
use NorthslopePL\Metassione\Tests\Fixtures\Blog\Comment;
use NorthslopePL\Metassione\Tests\Fixtures\Blog\Post;
use NorthslopePL\Metassione\Tests\Fixtures\Blog\TestBlogBuilder;
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
		$this->objectFiller = new POPOObjectFiller(new ClassDefinitionBuilder(new ClassPropertyFinder()), new PropertyValueCaster());
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

	public function testFillingComplexObjectHierarchy()
	{
		$blogBuilder = new TestBlogBuilder();
		$rawBlog = $blogBuilder->buildBlogAsStdClass();

		$blog = new Blog();
		$this->objectFiller->fillObjectWithRawData($blog, $rawBlog);

		$this->assertInstanceOf(Author::class, $blog->getAuthor());

		foreach ($blog->getPosts() as $post) {
			$this->assertInstanceOf(Post::class, $post);

			foreach ($post->getComments() as $comment) {
				$this->assertInstanceOf(Comment::class, $comment);
			}
		}
	}
}
