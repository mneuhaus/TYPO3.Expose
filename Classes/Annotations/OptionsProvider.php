<?php
namespace TYPO3\Expose\Annotations;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Expose".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @Annotation
 */
final class OptionsProvider implements SingleAnnotationInterface {

	/**
	 * @var integer
	 */
	public $name = '';

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * @param array $values
	 */
	public function __construct(array $values = array()) {
		foreach ($values as $key => $value) {
			$key = strtolower($key);
			$values[$key] = $value;
			$this->{$key} = $value;
		}
		$this->name = isset($values['value']) ? $values['value'] : $this->name;
		$this->name = isset($values['name']) ? $values['name'] : $this->name;
		$this->options = isset($values['options']) ? $values['options'] : $this->options;
		if (class_exists(sprintf('\\TYPO3\\Expose\\OptionsProvider\\%sOptionsProvider', $this->name))) {
			$this->name = sprintf('\\TYPO3\\Expose\\OptionsProvider\\%sOptionsProvider', $this->name);
		}
		if (class_exists(sprintf('\\TYPO3\\Expose\\OptionsProvider\\%s', $this->name))) {
			$this->name = sprintf('\\TYPO3\\Expose\\OptionsProvider\\%s', $this->name);
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->name;
	}
}

?>