<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Blog;

class Comment
{
	/**
	 * @var string
	 */
	private $authorName;

	/**
	 * @var string
	 */
	private $contents;

	/**
	 * @param string $authorName
	 */
	public function setAuthorName($authorName)
	{
		$this->authorName = $authorName;
	}

	/**
	 * @return string
	 */
	public function getAuthorName()
	{
		return $this->authorName;
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
}
