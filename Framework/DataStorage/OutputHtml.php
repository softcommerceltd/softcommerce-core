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
    private $dataOutputToHtml;

    /**
     * @var Status
     */
    private $statusOptions;

    /**
     * @var int
     */
    private $index;

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
     * @param array $format
     * @return string
     */
    public function execute(array $data, $format = [])
    {
        $this->executeBefore($format);

        if (isset($format[self::HTML_WRAPPER])) {
            $class = isset($format[self::HTML_WRAPPER_CLASS])
                ? $this->getClassTag($format[self::HTML_WRAPPER_CLASS])
                : null;
            $this->dataOutputToHtml = "<{$format[self::HTML_WRAPPER]} $class>";
        }

        $this->generateDataOutput($data);

        if (isset($format[self::HTML_WRAPPER])) {
            $this->dataOutputToHtml .= "</{$format[self::HTML_WRAPPER]}>";
        }

        $this->executeAfter();

        return $this->dataOutputToHtml;
    }

    /**
     * @param $format
     * @return $this
     */
    private function executeBefore($format)
    {
        $this->index = 0;
        $this->format = $format;
        $this->dataOutputToHtml = '';
        return $this;
    }

    /**
     * @return $this
     */
    private function executeAfter()
    {
        if (isset($this->format[self::LINE_BREAK])) {
            $this->dataOutputToHtml = substr($this->dataOutputToHtml, 0, -strlen($this->format[self::LINE_BREAK]));
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
        if ($this->index > self::LIMIT) {
            return $this;
        }

        $this->index++;
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
     * @param $data
     * @param string|null $status
     * @return $this
     */
    private function setDataOutput($data, $status = Status::SUCCESS)
    {
        $htmlTag = $this->format[self::HTML_TAG] ?? '%1';
        $index = explode('%', $htmlTag);
        $replaceSearch = [];
        for ($i = 1; $i <= count($index)-1; $i++) {
            $replaceSearch[] = "%$i";
        }

        if (!is_array($data)) {
            count($replaceSearch) > 1
                ? $replaceWith = [$status, $data]
                : $replaceWith = [$data];
            $this->dataOutputToHtml .= str_replace($replaceSearch, $replaceWith, $htmlTag);
            if (isset($this->format[self::LINE_BREAK])) {
                $this->dataOutputToHtml .= $this->format[self::LINE_BREAK];
            }
            return $this;
        }

        $k = 1;
        foreach ($data as $item) {
            count($replaceSearch) > 1
                ? $replaceWith = [$status, $item]
                : $replaceWith = [$item];
            $this->dataOutputToHtml .= str_replace($replaceSearch, $replaceWith, $htmlTag);
            if (isset($this->format[self::LINE_BREAK]) && count($data) > $k) {
                $this->dataOutputToHtml .= $this->format[self::LINE_BREAK];
            }
            $k++;
        }

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
}
