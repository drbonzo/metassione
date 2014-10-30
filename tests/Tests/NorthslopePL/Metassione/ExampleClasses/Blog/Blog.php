<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses\Blog;

class Blog
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var \Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Author
	 */
	private $author;

	/**
	 * @var array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Post[]
	 */
	private $posts;

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
	 * @param array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Post[] $posts
	 */
	public function setPosts($posts)
	{
		$this->posts = $posts;
	}

	/**
	 * @return array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Post[]
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @param \Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Author $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * @return \Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Author
	 */
	public function getAuthor()
	{
		return $this->author;
	}

}
