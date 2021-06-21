<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

use Magento\Framework\Phrase;
use Plenty\Core\Framework\MessageStorageInterface;
use Plenty\Core\Model\Source\Status;

/**
 * @inheritdoc
 */
class OutputHtml implements OutputHtmlInterface
{
    const LIMIT = 1000;

    /**
     * @var mixed
     */
    private $format;

    /**
     * @var string
     */
    private $dataHtml;

    /**
     * @var array
     */
    private $flag;

    /**
     * @var string|int|null
     */
    private static $entityGroup;

    /**
     * @param array $data
     * @param array $format
     * @return string
     */
    public function execute(array $data, array $format = [])
    {
        $this->executeBefore($format)
            ->generateDataOutput($data)
            ->executeAfter();

        return $this->dataHtml;
    }

    /**
     * @param $format
     * @return $this
     */
    private function executeBefore($format)
    {
        $this->format = $format;
        $this->dataHtml = '';
        $this->flag = [];
        return $this;
    }

    /**
     * @return $this
     */
    private function executeAfter()
    {
        if (isset($this->format[self::LINE_BREAK])) {
            $this->dataHtml = substr($this->dataHtml, 0, -strlen($this->format[self::LINE_BREAK]));
        }

        if (isset($this->format[self::HTML_WRAPPER])) {
            $this->dataHtml .= "</{$this->format[self::HTML_WRAPPER]}>";
        }
        return $this;
    }

    /**
     * @param $data
     * @param null $status
     * @return $this
     */
    private function generateDataOutput($data, $status = null)
    {
        foreach ($data as $item) {
            if (!is_array($item)) {
                $this->setDataOutput($this->parseToString($item), $status);
                continue;
            }

            if (!isset($item[MessageStorageInterface::ENTITY],
                $item[MessageStorageInterface::STATUS],
                $item[MessageStorageInterface::MESSAGE]
            )) {
                $this->generateDataOutput($item, $status);
                continue;
            }

            $entity = $item[MessageStorageInterface::ENTITY];
            $status = $item[MessageStorageInterface::STATUS];
            $message = $item[MessageStorageInterface::MESSAGE];

            $this->setWrapper($entity);
            self::$entityGroup = $entity;

            if (is_array($message)) {
                $this->generateDataOutput($item, $status);
                continue;
            }

            $this->setDataOutput($this->parseToString($message), $status);
            $this->setWrapper($entity);
        }

        return $this;
    }

    /**
     * @param string $data
     * @param string|null $status
     * @return $this
     */
    private function setDataOutput(string $data, ?string $status = Status::SUCCESS)
    {
        $htmlTag = $this->format[self::HTML_TAG] ?? '%1';
        $index = explode('%', $htmlTag);
        $replaceSearch = [];
        for ($i = 1; $i <= count($index)-1; $i++) {
            $replaceSearch[] = "%$i";
        }

        count($replaceSearch) > 1
            ? $replaceWith = [$status, $data]
            : $replaceWith = [$data];
        $this->dataHtml .= str_replace($replaceSearch, $replaceWith, $htmlTag);
        if (isset($this->format[self::LINE_BREAK])) {
            $this->dataHtml .= $this->format[self::LINE_BREAK];
        }
        return $this;
    }

    /**
     * @param $entityGroup
     * @return $this
     */
    private function setHeader($entityGroup)
    {
        if (isset($this->flag[self::HTML_HEADER_TAG][$entityGroup])
            || !$headerTag = $this->format[self::HTML_HEADER_TAG] ?? null
        ) {
            return $this;
        }

        $this->dataHtml .= str_replace('%s', $entityGroup, $headerTag);
        $this->flag[self::HTML_HEADER_TAG][$entityGroup] = true;
        return $this;
    }

    /**
     * @param $entityGroup
     * @return $this
     */
    private function setWrapper($entityGroup)
    {
        if (!isset($this->format[self::HTML_WRAPPER])) {
            return $this;
        }

        if (self::$entityGroup == $entityGroup) {
            return $this;
        }

        if (isset($this->flag[self::HTML_WRAPPER_CLASS][self::$entityGroup])) {
            $this->dataHtml .= "</{$this->format[self::HTML_WRAPPER]}>";
        }

        $this->setHeader($entityGroup);
        $class = isset($this->format[self::HTML_WRAPPER_CLASS])
            ? $this->getClassTag($this->format[self::HTML_WRAPPER_CLASS])
            : null;
        $this->dataHtml .= "<{$this->format[self::HTML_WRAPPER]} $class>";
        $this->flag[self::HTML_WRAPPER_CLASS][$entityGroup] = true;

        return $this;
    }

    /**
     * @param $classTag
     * @return string
     */
    private function getClassTag($classTag): string
    {
        $classTag = is_array($classTag)
            ? implode(' ', $classTag)
            : $classTag;
        return "class=\"{$classTag}\"";
    }

    /**
     * @param $item
     * @return string
     */
    private function parseToString($item): string
    {
        if (is_array($item)) {
            $item = implode(' | ', $item);
        } elseif ($item instanceof Phrase) {
            $item = $item->render();
        } else {
            $item = (string) $item;
        }
        return $item;
    }
}
