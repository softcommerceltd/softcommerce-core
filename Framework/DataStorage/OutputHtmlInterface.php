<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\DataStorage;

/**
 * Interface OutputHtmlInterface used to output
 * data to html.
 */
interface OutputHtmlInterface
{
    public const HTML_WRAPPER = 'wrapper';
    public const HTML_WRAPPER_CLASS = 'wrapper_class';
    public const HTML_TAG = 'tag';
    public const HTML_SEPARATOR = 'separator';
    public const LINE_BREAK = 'break';
    public const OUTPUT = 'output';

    /**
     * @param array $data
     * @param array $format
     * @return string
     */
    public function execute(array $data, $format = []);
}
