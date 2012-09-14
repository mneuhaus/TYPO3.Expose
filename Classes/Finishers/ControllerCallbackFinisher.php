<?php
namespace TYPO3\Expose\Finishers;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Expose".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * This finisher redirects to another Controller.
 */
class ControllerCallbackFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

	/**
	 * @return void
	 * @throws \TYPO3\FLOW3\Mvc\Exception\ForwardException
	 */
	public function executeInternal() {
		$formRuntime = $this->finisherContext->getFormRuntime();

		$objectArguments = $formRuntime->getFormState()->getFormValue('objects');
		if (isset($this->options['objectIdentifiers'])) {
			$objectArguments = \TYPO3\FLOW3\Utility\Arrays::arrayMergeRecursiveOverrule($objectArguments, $this->options['objectIdentifiers']);
		}

		$nextRequest = clone $formRuntime->getRequest()->getParentRequest();
		$nextRequest->setArgument('@action', $this->parseOption("callbackAction"));
		$nextRequest->setArgument('objects', $objectArguments);

		$forwardException = new \TYPO3\FLOW3\Mvc\Exception\ForwardException();
		$nextRequest->setDispatched(FALSE);
		$forwardException->setNextRequest($nextRequest);
		throw $forwardException;
	}
}

?>