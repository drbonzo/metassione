<?php
namespace NorthslopePL\Metassione\Tests\Fixtures\Blog;

class Blog
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Blog\Author
	 */
	private $author;

	/**
	 * @var \NorthslopePL\Metassione\Tests\Fixtures\Blog\Post[]
	 */
	private $posts = [];

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param array|\NorthslopePL\Metassione\Tests\Fixtures\Blog\Post[] $posts
	 */
	public function setPosts($posts)
	{
		$this->posts = $posts;
	}

	/**
	 * @return array|\NorthslopePL\Metassione\Tests\Fixtures\Blog\Post[]
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @param \NorthslopePL\Metassione\Tests\Fixtures\Blog\Author $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * @return \NorthslopePL\Metassione\Tests\Fixtures\Blog\Author
	 */
	public function getAuthor()
	{
		return $this->author;
	}

}
