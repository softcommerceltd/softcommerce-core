<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftCommerce\Core\Framework\DataStorage;

/**
 * Interface OutputMessageManagerInterface used to output
 * data to message manager
 * @see \Magento\Framework\Message\ManagerInterface
 */
interface OutputMessageManagerInterface
{
    /**
     * @param array $data
     * @param string|null $lineBreak
     */
    public function execute(array $data, ?string $lineBreak = null): void;
}
