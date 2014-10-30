<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses\Blog;

class Post
{
	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $contents;

	/**
	 * @var array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Comment[]
	 */
	private $comments;

	/**
	 * @param array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Comment[] $comments
	 */
	public function setComments($comments)
	{
		$this->comments = $comments;
	}

	/**
	 * @return array|\Tests\NorthslopePL\Metassione\ExampleClasses\Blog\Comment[]
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * @param string $contents
	 */
	public function setContents($contents)
	{
		$this->contents = $contents;
	}

	/**
	 * @return string
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

}
