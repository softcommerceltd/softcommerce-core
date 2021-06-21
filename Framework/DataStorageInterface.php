<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework;

/**
 * Interface DataStorageInterface used to store
 * and retrieve data.
 */
interface DataStorageInterface
{
    /**
     * @param int|string|null $key
     * @return array|int|string|null|mixed
     */
    public function getData($key = null);

    /**
     * @param int|string|array|mixed $data
     * @param int|string|null $key
     * @return $this
     */
    public function setData($data, $key = null);

    /**
     * @param array|string $data
     * @param int|string|null $key
     * @return $this
     */
    public function addData($data, $key = null);

    /**
     * @param $data
     * @param null $key
     * @return $this
     */
    public function mergeData($data, $key = null);

    /**
     * @param $data
     * @param null $key
     * @return $this
     */
    public function mergeRecusiveData($data, $key = null);

    /**
     * @param int|string|null $key
     * @return $this
     */
    public function resetData($key = null);
}
