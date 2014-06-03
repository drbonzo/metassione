<?php
namespace NorthslopePL\Metassione\Tests;

use NorthslopePL\Metassione\POPOConverter;
use NorthslopePL\Metassione\Tests\Blog\Blog;
use NorthslopePL\Metassione\Tests\Blog\Comment;
use NorthslopePL\Metassione\Tests\Blog\Post;

class POPOConverterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var POPOConverter
	 */
	private $converter;

	protected function setUp()
	{
		$this->converter = new POPOConverter();
	}

	public function testConvertingEmptyObjectGivesEmptyStdClass()
	{
		$emptyObject = new EmptyKlass();
		$result = $this->converter->convert($emptyObject);

		$this->assertEquals(new \stdClass(), $result);
	}

	public function testConvertingArrayOfEmptyObjectsGivesArrayOfEmptyStdClasses()
	{
		$emptyObjects = [new EmptyKlass(), new EmptyKlass(), new EmptyKlass()];
		$expected = [new \stdClass(), new \stdClass(), new \stdClass()];

		$this->assertEquals($expected, $this->converter->convert($emptyObjects));
	}

	public function testConversionOfSimpleClassWithBasicTypeValues()
	{
		$simpleObject = new SimpleKlass();
		$simpleObject->setNullValue(null);
		$simpleObject->setBoolValue(true);
		$simpleObject->setIntValue(42);
		$simpleObject->setFloatValue(12.95);
		$simpleObject->setStringValue('Lorem ipsum');

		$expectedObject = new \stdClass();
		$expectedObject->nullValue = null;
		$expectedObject->boolValue = true;
		$expectedObject->intValue = 42;
		$expectedObject->floatValue = 12.95;
		$expectedObject->stringValue = 'Lorem ipsum';

		$actual = $this->converter->convert($simpleObject);
		$this->assertEquals(print_r($expectedObject, 1), print_r($actual, 1));
		$this->assertEquals($expectedObject, $actual);
	}

	public function testConvertingObjectWithPropertiesOfTypeArrays()
	{
		// input
		$arrayedObject = new ArrayedKlass();
		$arrayedObject->setNumbers([1, 2, 3, 4, 5]);

		$object6 = new OnePropertyKlass();
		$object6->setValue(6);
		$object7 = new OnePropertyKlass();
		$object7->setValue(7);
		$object8 = new OnePropertyKlass();
		$object8->setValue(8);
		$objects = [$object6, $object7, $object8];

		$arrayedObject->setObjects($objects);

		// expected
		$expectedObject = new \stdClass();
		$expectedObject->numbers = [1, 2, 3, 4, 5];
		$expectedObject->objects = [];
		$expectedObject->objects[] = (object)['value' => 6];
		$expectedObject->objects[] = (object)['value' => 7];
		$expectedObject->objects[] = (object)['value' => 8];

		// result
		$actual = $this->converter->convert($arrayedObject);
		$this->assertEquals($expectedObject, $actual);
	}

	public function testComplexObjectHierarchyIsConvertedToComplesStdClassesAndArrays()
	{
		$blog = $this->buildBlog();
		$expectedObject = new \stdClass();
		$expectedObject->name = 'PHP Programmers blog';

		$post1 = new \stdClass();
		{
			$post1->title = 'Post #1';
			$post1->contents = 'Lorem ipsum dolor sit amet: 1';

			$comment_1_1 = new \stdClass();
			$comment_1_1->authorName = 'Author_1.1';
			$comment_1_1->contents = 'Le comment no: 1.1';
			$comment_1_2 = new \stdClass();
			$comment_1_2->authorName = 'Author_1.2';
			$comment_1_2->contents = 'Le comment no: 1.2';

			$post1->comments = [$comment_1_1, $comment_1_2];
		}

		$post2 = new \stdClass();
		{
			$post2->title = 'Post #2';
			$post2->contents = 'Lorem ipsum dolor sit amet: 2';

			$comment_2_1 = new \stdClass();
			$comment_2_1->authorName = 'Author_2.1';
			$comment_2_1->contents = 'Le comment no: 2.1';
			$comment_2_2 = new \stdClass();
			$comment_2_2->authorName = 'Author_2.2';
			$comment_2_2->contents = 'Le comment no: 2.2';

			$post2->comments = [$comment_2_1, $comment_2_2];
		}

		$post3 = new \stdClass();
		{
			$post3->title = 'Post #3';
			$post3->contents = 'Lorem ipsum dolor sit amet: 3';

			$comment_3_1 = new \stdClass();
			$comment_3_1->authorName = 'Author_3.1';
			$comment_3_1->contents = 'Le comment no: 3.1';
			$comment_3_2 = new \stdClass();
			$comment_3_2->authorName = 'Author_3.2';
			$comment_3_2->contents = 'Le comment no: 3.2';

			$post3->comments = [$comment_3_1, $comment_3_2];
		}

		$expectedObject->posts = [$post1, $post2, $post3];

		$actual = $this->converter->convert($blog);
		$this->assertEquals(print_r($expectedObject, true), print_r($actual, true));
		$this->assertEquals($expectedObject, $actual);
	}

	/**
	 * @return Blog
	 */
	private function buildBlog()
	{
		$blog = new Blog();

		$blog->setName('PHP Programmers blog');

		$post1 = $this->buildPost(1);
		$post2 = $this->buildPost(2);
		$post3 = $this->buildPost(3);
		$posts = [$post1, $post2, $post3];
		$blog->setPosts($posts);

		return $blog;
	}

	/**
	 * @param $postNumber
	 * @return Post
	 */
	private function buildPost($postNumber)
	{
		$post = new Post();
		$post->setTitle('Post #' . $postNumber);
		$post->setContents('Lorem ipsum dolor sit amet: ' . $postNumber);

		$comments = [$this->buildComment($postNumber . '.' . 1), $this->buildComment($postNumber . '.' . 2)];
		$post->setComments($comments);

		return $post;
	}

	/**
	 * @param $commentNumber
	 * @return Comment
	 */
	private function buildComment($commentNumber)
	{
		$comment = new Comment();
		$comment->setAuthorName('Author_' . $commentNumber);
		$comment->setContents('Le comment no: ' . $commentNumber);
		return $comment;
	}
}

