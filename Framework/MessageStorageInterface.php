<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework;

use Plenty\Core\Model\Source\Status;

/**
 * Interface MessageStorageInterface used to store
 * messages as array data
 */
interface MessageStorageInterface
{
    public const ENTITY = 'entity';
    public const STATUS = 'status';
    public const MESSAGE = 'message';

    /**
     * @param int|string|null $entity
     * @param array $status
     * @return array
     */
    public function getData($entity = null, array $status = []): array;

    /**
     * @param string|array|mixed $message
     * @param int|string $entity
     * @param string $status
     * @return $this
     */
    public function addData($message, $entity, string $status = Status::SUCCESS);

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data);

    /**
     * @param array $data
     * @param int|string|null $key
     * @return $this
     */
    public function mergeData(array $data, $key = null);

    /**
     * @return array
     */
    public function getEntityIds(): array;

    /**
     * @param  int|string|null $key
     * @return $this
     */
    public function resetData($key = null);
}
