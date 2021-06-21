<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\DataStorage;

use Plenty\Core\Model\Source\Status;

/**
 * @inheritdoc
 */
class StatusPrediction implements StatusPredictionInterface
{
    /**
     * @param array $data
     * @param array|string $fallback
     * @return string
     */
    public function execute(array $data, $fallback = Status::SUCCESS): string
    {
        if ((array_key_exists(Status::SUCCESS, $data) && array_key_exists(Status::ERROR, $data))
            || array_key_exists(Status::WARNING, $data)
        ) {
            $status = Status::WARNING;
        } elseif (array_key_exists(Status::CRITICAL, $data) || array_key_exists(Status::ERROR, $data)) {
            $status = Status::ERROR;
        } elseif (array_key_exists(Status::NOTICE, $data) && !array_key_exists(Status::SUCCESS, $data)) {
            $status = Status::NOTICE;
        } elseif (array_key_exists(Status::SKIPPED, $data) && !array_key_exists(Status::ERROR, $data)) {
            $status = Status::SKIPPED;
        } else {
            $status = $fallback;
        }
        return $status;
    }
}
