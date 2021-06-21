<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

/**
 * Interface OutputArrayInterface used to output
 * data to array.
 */
interface OutputArrayInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array;
}
