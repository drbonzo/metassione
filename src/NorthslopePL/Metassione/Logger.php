<?php
namespace NorthslopePL\Metassione;

class Logger
{
	/**
	 * @var Timer
	 */
	private $currentTimer;

	/**
	 * @var Timer[]
	 */
	private $timerStack = [];

	/**
	 * @var Timer
	 */
	private $rootTimer;

	public function __construct()
	{
		$timer = new Timer('ROOT', microtime(true));
		$this->pushTimer($timer);
		$this->rootTimer = $timer;
	}

	public function startTimer($name)
	{
		$timer = new Timer($name, microtime(true));
		$this->pushTimer($timer);
	}

	private function pushTimer(Timer $timer)
	{
		if ($this->currentTimer)
		{
			$this->currentTimer->addTimer($timer);
		}
		array_push($this->timerStack, $timer);
		$this->currentTimer = $timer;
	}

	public function endTimer()
	{
		$timer = array_pop($this->timerStack);
		$timer->endTimer(microtime(true));

		$this->currentTimer = end($this->timerStack);
	}

	public function getRootTimer()
	{
		return $this->rootTimer;
	}
}


class Timer
{
	/**
	 * @var int
	 */
	private $startTime;

	/**
	 * @var int
	 */
	private $endTime;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Timer[]
	 */
	private $childTimers = [];

	public function __construct($name, $startTime)
	{
		$this->name = $name;
		$this->startTime = $startTime;
	}

	public function endTimer($endTime)
	{
		$this->endTime = $endTime;
	}

	public function addTimer(Timer $timer)
	{
		$this->childTimers[] = $timer;
	}

	/**
	 * @return int
	 */
	public function getDuration()
	{
		return $this->endTime - $this->startTime;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return Timer[]
	 */
	public function getChildtimers()
	{
		return $this->childTimers;
	}
}


class TimerPrinter
{
	public function printTimer(Timer $timer)
	{
		$depth = 0;

		$contents = $this->doPrintTimer($timer, $depth);
		print(join("\n", $contents));
	}

	private function doPrintTimer(Timer $timer, $depth)
	{
		$contents = [];

		$childrenContents = [];
		foreach ($timer->getChildtimers() as $childTimer)
		{
			$childContents = $this->doPrintTimer($childTimer, $depth + 1);
			$childrenContents = array_merge($childrenContents, $childContents);
		}

		$contents[] = sprintf("%s%s[%.5f] %s", str_repeat('--', $depth), ($depth > 0 ? ' ' : ''), $timer->getDuration(), $timer->getName());
		$contents = array_merge($contents, $childrenContents);

		return $contents;
	}
}
