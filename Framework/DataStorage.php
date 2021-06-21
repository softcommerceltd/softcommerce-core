<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework;

/**
 * @inheritdoc
 */
class DataStorage implements DataStorageInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * DataStorage constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function getData($key = null)
    {
        return null !== $key
            ? ($this->data[$key] ?? null)
            : ($this->data ?: []);
    }

    /**
     * @inheritdoc
     */
    public function setData($data, $key = null)
    {
        null !== $key
            ? $this->data[$key] = $data
            : $this->data = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addData($data, $key = null)
    {
        null !== $key
            ? $this->data[$key][] = $data
            : $this->data[] = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function mergeData($data, $key = null)
    {
        null !== $key
            ? $this->data[$key] = array_merge($this->data[$key] ?? [], is_array($data) ? $data : [$data])
            : $this->data = array_merge($this->data ?: [], is_array($data) ? $data : [$data]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function mergeRecusiveData($data, $key = null)
    {
        null !== $key
            ? $this->data[$key] = array_merge_recursive(
                $this->data[$key] ?? [],
                is_array($data) ? $data : [$data]
            )
            : $this->data = array_merge_recursive($this->data ?: [], is_array($data) ? $data : [$data]);
        return $this;
    }

    /**
     * @inheritdoc
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
