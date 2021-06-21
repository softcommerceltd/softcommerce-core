<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework;

use Plenty\Core\Model\Source\Status;

/**
 * @inheritdoc
 */
class MessageStorage implements MessageStorageInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param int|string|null $entity
     * @param array $status
     * @return array
     */
    public function getData($entity = null, array $status = []): array
    {
        $data = null !== $entity
            ? ($this->data[$entity] ?? [])
            : $this->data;

        if (empty($status)) {
            return $data;
        }

        $resultData = [];
        foreach ($data as $entity => $items) {
            $result = array_filter($items, function ($item) use ($status) {
                return isset($item[self::STATUS]) && in_array($item[self::STATUS], $status);
            });
            if (!empty($result)) {
                $resultData[$entity] = $result;
            }
        }

        return $resultData;
    }

    /**
     * @param string|array|mixed $message
     * @param int|string $entity
     * @param string $status
     * @return $this
     */
    public function addData($message, $entity, string $status = Status::SUCCESS)
    {
        $this->data[$entity][] = [
            self::ENTITY => $entity,
            self::STATUS => $status,
            self::MESSAGE => $message
        ];
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $data
     * @param int|string|null $key
     * @return $this
     */
    public function mergeData(array $data, $key = null)
    {
        null !== $key
            ? $this->data[$key] = array_merge_recursive($this->data[$key] ?? [], $data[$key] ?? [])
            : $this->data = array_merge_recursive($this->data, $data);
        return $this;
    }

    /**
     * @return array
     */
    public function getEntityIds(): array
    {
        return array_unique(array_keys($this->data));
    }

    /**
     * @param  int|string|null $key
     * @return $this
     */
    public function resetData($key = null)
    {
        if (null === $key) {
            $this->data = [];
        } elseif (isset($this->data[$key])) {
            unset($this->data[$key]);
        }

        return $this;
    }
}
