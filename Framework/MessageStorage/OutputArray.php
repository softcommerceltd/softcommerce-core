<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

use Plenty\Core\Framework\MessageStorageInterface;

/**
 * @inheritdoc
 */
class OutputArray implements OutputArrayInterface
{
    /**
     * @var array|string
     */
    private $data;

    /**
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        $this->data = [];
        foreach ($data as $item) {
            $this->generateDataOutput($item);
        }
        return $this->data;
    }

    /**
     * @param array $data
     */
    private function generateDataOutput(array $data): void
    {
        foreach ($data as $item) {
            if (!isset($item[MessageStorageInterface::STATUS], $item[MessageStorageInterface::MESSAGE])) {
                continue;
            }
            $this->data[$item[MessageStorageInterface::STATUS]][] = $item[MessageStorageInterface::MESSAGE];
        }
    }
}
