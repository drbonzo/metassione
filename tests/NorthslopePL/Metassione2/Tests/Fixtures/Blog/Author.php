<?php
namespace NorthslopePL\Metassione2\Tests\Fixtures\Blog;

class Author
{
	/**
	 * @var string
	 */
	private $name;

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

}
