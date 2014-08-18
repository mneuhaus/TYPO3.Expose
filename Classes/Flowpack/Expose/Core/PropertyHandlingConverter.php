<?php
namespace Flowpack\Expose\Core;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Flowpack\Expose\Reflection\ClassSchema;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

/**
 * This converter transforms arrays or strings to persistent objects. It does the following:
 *
 * - If the input is string, it is assumed to be a UUID. Then, the object is fetched from persistence.
 * - If the input is array, we check if it has an identity property.
 *
 * - If the input has an identity property and NO additional properties, we fetch the object from persistence.
 * - If the input has an identity property AND additional properties, we fetch the object from persistence,
 *   and set the sub-properties. We only do this if the configuration option "CONFIGURATION_MODIFICATION_ALLOWED" is TRUE.
 * - If the input has NO identity property, but additional properties, we create a new object and return it.
 *   However, we only do this if the configuration option "CONFIGURATION_CREATION_ALLOWED" is TRUE.
 *
 * @api
 * @Flow\Scope("singleton")
 */
class PropertyHandlingConverter extends PersistentObjectConverter {

	/**
	 * @var integer
	 */
	protected $priority = 10;

	/**
	 * @var string
	 */
	protected $targetTypes = array();

	/**
	 * We can only convert if the $targetType is either tagged with entity or value object.
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public function canConvertFrom($source, $targetType) {
		$this->targetTypes[] = $targetType;
		return parent::canConvertFrom($source, $targetType);
	}

	/**
	 * All properties in the source array except __identity are sub-properties.
	 *
	 * @param mixed $source
	 * @return array
	 */
	public function getSourceChildPropertiesToBeConverted($source) {
		$targetType = array_pop($this->targetTypes);

		$originalObject = $this->fetchObjectFromPersistence($source['__identity'], $targetType);
		$childProperties = parent::getSourceChildPropertiesToBeConverted($source);

		$schema = new ClassSchema($targetType);
		foreach ($childProperties as $propertyName => $propertyValue) {
			$property = $schema->getProperty($propertyName);
			$handlerClassName = $property->getHandler();
			if ($handlerClassName === NULL) {
				continue;
			}
			$handler = new $handlerClassName($originalObject, $propertyName);
			$childProperties[$propertyName] = $handler->onSubmit($propertyValue);
		}

		return $childProperties;
	}
}
