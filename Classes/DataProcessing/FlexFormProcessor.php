<?php
declare(strict_types = 1);

/*
 * This file is part of the package sci/sci-api.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Sci\SciApi\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Minimal TypoScript configuration
 * Process field pi_flexform and overrides the values stored in data
 *
 * 10 = Sci\SciApi\DataProcessing\FlexFormProcessor
 *
 *
 * Advanced TypoScript configuration
 * Process field assigned in fieldName and stores processed data to new key
 *
 * 10 = Sci\SciApi\DataProcessing\FlexFormProcessor
 * 10 {
 *   fieldName = pi_flexform
 *   as = flexform
 * }
 */
class FlexFormProcessor implements DataProcessorInterface
{
    /**
     * @var FlexFormService
     */
    protected $flexFormService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
    }

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $fieldName = 'content_block';

        // Process Flexform
        $originalValue = $processedData['data'][$fieldName];
        if (!is_string($originalValue)) {
            return $processedData;
        }
        $flexformData = $this->flexFormService->convertFlexFormContentToArray($originalValue);

        debug($flexformData);

        // Set the target variable
        $processedData = array_merge($processedData, $flexformData);

        return $processedData;
    }
}