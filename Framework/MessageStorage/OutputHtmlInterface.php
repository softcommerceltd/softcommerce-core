<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftCommerce\Core\Framework\MessageStorage;

/**
 * Interface OutputHtmlInterface used to output
 * data to html.
 */
interface OutputHtmlInterface
{
    public const HTML_WRAPPER = 'wrapper';
    public const HTML_WRAPPER_CLASS = 'wrapper_class';
    public const HTML_TAG = 'tag';
    public const HTML_HEADER_TAG = 'header_tag';
    public const HTML_SEPARATOR = 'separator';
    public const LINE_BREAK = 'break';
    public const OUTPUT = 'output';

    /**
     * @param array $data
     * @param array $format
     * @return string
     */
    public function execute(array $data, array $format = []);
}
