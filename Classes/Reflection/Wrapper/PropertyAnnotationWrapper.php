<?php
namespace Foo\ContentManagement\Reflection\Wrapper;

/*                                                                        *
 * This script belongs to the Foo.ContentManagement package.              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
class PropertyAnnotationWrapper extends AbstractAnnotationWrapper {
	/**
	 * @var \TYPO3\FLOW3\Configuration\ConfigurationManager
	 * @FLOW3\Inject
	 */
	protected $configurationManager;

	public function getLabel() {
		return ucfirst($this->get("property"));
	}

	public function getType() {
		preg_match("/<(.+)>/", $this->get("type"), $matches);
		if(!empty($matches)){
			return ltrim($matches[1],"\\");
		}else{
			return strval($this->get("type"));
		}
	}

	public function isRelationProperty() {
		#return $this->containsKey("manyToMany") || 
		return $this->containsKey("manyToOne");
	}

	public function setProperty($property) {
		$this->set("property", $property);
	}

	public function getElement() {
		$raw = strval($this->getType());
		
		$element = null;
		$default = "TYPO3.Form:SingleLineText";
		
		$mappings = $this->configurationManager->getConfiguration(\TYPO3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, "Foo.ContentManagement.Mapping.Elements");
		
		if( ! empty($mappings) ) {
			if($this->has("Element"))
				$element = strval($this->get("Element"));
			
			if( $element === null && isset($mappings[$raw]) ) {
				$element = $mappings[$raw];
			}
			
			if( $element === null && isset($mappings[strtolower($raw)]) ) {
				$element = $mappings[$raw];
			}
			
			if( $element === null && isset($mappings[ucfirst($raw)]) ) {
				$element = $mappings[$raw];
			}
			
			if( $element === null){
				foreach($mappings as $pattern => $element) {
					if( preg_match("/" . $pattern . "/", $raw) > 0 ) {
						break;
					}
				}
			}
		}
		
		if( $element === null && $default !== null )
			$element = $default;
		
		if($element === null)
			$element = $raw;
		
		return $element;
	}

	public function getValue() {
		return $this->get("Value");
	}
}

?>