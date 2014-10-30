<?php
namespace NorthslopePL\Metassione;

use NorthslopePL\Metassione\ClassStructure\ClassStructure;
use NorthslopePL\Metassione\ClassStructure\ClassStructureException;

class FileReflectionCache implements ReflectionCache
{
	/**
	 * @var ReflectionCache
	 */
	private $internalReflectionCache;

	/**
	 * @var string
	 */
	private $cacheDir;

	/**
	 * @var ClassStructure[]
	 */
	private $classStructureCache = [];

	/**
	 * @param ReflectionCache $internalReflectionCache
	 * @param string $cacheDir
	 */
	public function __construct(ReflectionCache $internalReflectionCache, $cacheDir)
	{
		$this->internalReflectionCache = $internalReflectionCache;
		$this->cacheDir = $cacheDir;
	}

	/**
	 * @param string $classname
	 *
	 * @return ClassStructure
	 *
	 * @throws ClassStructureException
	 */
	public function getClassStructureForClassname($classname)
	{
		if (!isset($this->classStructureCache[$classname]))
		{
			$classStructure = $this->loadClassStructureFromFile($classname);
			$this->classStructureCache[$classname] = $classStructure;
		}

		return $this->classStructureCache[$classname];
	}

	/**
	 * @param string $className
	 * @param string $propertyName
	 *
	 * @return \ReflectionProperty
	 */
	public function getReflectionPropertyForClassnameAndPropertyName($className, $propertyName)
	{
		return $this->internalReflectionCache->getReflectionPropertyForClassnameAndPropertyName($className, $propertyName);
	}

	/**
	 * @param string $classname
	 *
	 * @return object
	 *
	 * @throws ObjectFillingException
	 */
	public function buildNewInstanceOfClass($classname)
	{
		return $this->internalReflectionCache->buildNewInstanceOfClass($classname);
	}

	private function loadClassStructureFromFile($classname)
	{
		$baseFileName = str_replace('\\', '__', $classname);
		$fileName = sprintf('%s/%s.dat', $this->cacheDir, $baseFileName);

		if (file_exists($fileName))
		{
			// read cache from file
			$fileContents = file_get_contents($fileName);
			$classStructure = unserialize($fileContents);

			if ($classStructure === false)
			{
				return $this->generateAndSaveClassStructureToFileForClass($classname, $fileName);
			}
			else
			{
				return $classStructure;
			}
		}
		else
		{
			// generate cache
			return $this->generateAndSaveClassStructureToFileForClass($classname, $fileName);
		}
	}

	/**
	 * @param string $className
	 * @param string $fileName
	 * @return ClassStructure
	 */
	private function generateAndSaveClassStructureToFileForClass($className, $fileName)
	{
		$classStructure = $this->internalReflectionCache->getClassStructureForClassname($className);
		file_put_contents($fileName, serialize($classStructure));

		return $classStructure;
	}
}
