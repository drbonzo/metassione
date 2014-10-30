<?php
namespace Tests\NorthslopePL\Metassione\ExampleClasses\Blog;

class TestBlogBuilder
{
	/**
	 * @return Blog
	 */
	public function buildBlog()
	{
		$blog = new Blog();

		$blog->setName('PHP Programmers blog');
		$blogAuthor = new Author();
		$blogAuthor->setName('Anonymous Blogger');
		$blog->setAuthor($blogAuthor);

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

	/**
	 * @return \stdClass
	 */
	public function buildBlogAsStdClass()
	{
		$blog = new \stdClass();
		$blog->name = 'PHP Programmers blog';

		$blogAuthor = new \stdClass();
		$blogAuthor->name = 'Anonymous Blogger';
		$blog->author = $blogAuthor;

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

		$blog->posts = [$post1, $post2, $post3];

		return $blog;
	}
}
