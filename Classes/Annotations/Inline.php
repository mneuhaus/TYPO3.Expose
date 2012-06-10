<?php
namespace Foo\ContentManagement\Annotations;

/*                                                                        *
 * This script belongs to the Foo.ContentManagement package.              *
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
final class Inline implements SingleAnnotationInterface {
	/**
	 * @var string
	 **/
	protected $variant = "Foo.ContentManagement:InlineTabular";
	
	/**
	 * @param string $value
	 */
	public function __construct(array $values = array()) {
		$this->variant = isset($values['value']) && $values['value'] !== true ? $values['value'] : $this->variant;
		$this->variant = isset($values['variant']) ? $values['variant'] : $this->variant;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function getVariant() {
		return $this->variant;
	}
}

?>