<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\DataStorage;

use Magento\Framework\Phrase;
use Plenty\Core\Model\Source\Status;

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
     * @var Status
     */
    private $statusOptions;

    /**
     * OutputHtml constructor.
     * @param Status $statusOptions
     */
    public function __construct(Status $statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @param array $data
     * @return array|string
     */
    public function execute(array $data)
    {
        $this->data = [];
        $this->generateDataOutput($data);
        return $this->data;
    }

    /**
     * @param $data
     * @param null $status
     * @return $this
     */
    private function generateDataOutput($data, $status = null)
    {
        if (!is_array($data)) {
            $this->setDataOutput($data, $status);
            return $this;
        }

        foreach ($data as $key => $item) {
            if (is_string($key) && in_array($key, $this->statusOptions->getAllOptions())) {
                $status = $key;
            }

            if (is_array($item)) {
                $this->generateDataOutput($item, $status);
                continue;
            }

            if ($item instanceof Phrase) {
                $item = $item->render();
            }

            $this->setDataOutput($item, $status);
        }

        return $this;
    }

    /**
     * @param $result
     * @param string|int|null $key
     * @return $this
     */
    private function setDataOutput($result, $key = null)
    {
        if (null !== $key) {
            $this->data[$key][] = $result;
        } else {
            $this->data[] = $result;
        }
        return $this;
    }
}
