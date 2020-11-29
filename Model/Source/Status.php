<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\Core\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package SoftCommerce\Core\Model\Source
 */
class Status implements OptionSourceInterface
{
    const ERROR             = 'error';
    const FAILED            = 'failed';
    const MISSED            = 'missed';
    const PENDING           = 'pending';
    const COMPLETE          = 'complete';
    const RUNNING           = 'running';
    const PROCESSING        = 'processing';
    const SUCCESS           = 'success';
    const NOTICE            = 'notice';
    const SKIPPED           = 'skipped';
    const STOPPED           = 'stopped';
    const CREATED           = 'created';
    const UPDATED           = 'updated';
    const UNKNOWN           = 'unknown';
    const WARNING           = 'warning';

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            self::ERROR,
            self::FAILED,
            self::MISSED,
            self::PENDING,
            self::COMPLETE,
            self::RUNNING,
            self::PROCESSING,
            self::SUCCESS,
            self::SKIPPED,
            self::STOPPED,
            self::CREATED,
            self::UPDATED,
            self::UNKNOWN,
            self::WARNING
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ERROR, 'label' => __('Error')],
            ['value' => self::FAILED, 'label' => __('Failed')],
            ['value' => self::MISSED, 'label' => __('Missed')],
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::COMPLETE, 'label' => __('Complete')],
            ['value' => self::RUNNING, 'label' => __('Running')],
            ['value' => self::PROCESSING, 'label' => __('Processing')],
            ['value' => self::SUCCESS, 'label' => __('Success')],
            ['value' => self::SKIPPED, 'label' => __('Skipped')],
            ['value' => self::STOPPED, 'label' => __('Stopped')],
            ['value' => self::UPDATED, 'label' => __('Updated')]
        ];
    }

    /**
     * @return array
     */
    public function toOptionArrayScheduleStatus()
    {
        return [
            ['value' => self::ERROR, 'label' => __('Error')],
            ['value' => self::MISSED, 'label' => __('Missed')],
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::SUCCESS, 'label' => __('Success')],
            ['value' => self::RUNNING, 'label' => __('Running')]
        ];
    }

    /**
     * @return array
     */
    public function toOptionHashScheduleStatuses()
    {
        $options =[];
        foreach($this->toOptionArrayScheduleStatus() as $index => $item) {
            $options[$item['value']] = $item['label'];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toOptionArrayScheduleHistoryStatus()
    {
        return [
            ['value' => self::ERROR, 'label' => __('Error')],
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::COMPLETE, 'label' => __('Complete')],
            ['value' => self::PROCESSING, 'label' => __('Processing')]
        ];
    }

    /**
     * @return array
     */
    public function toOptionHashScheduleHistoryStatuses()
    {
        $options =[];
        foreach($this->toOptionArrayScheduleHistoryStatus() as $index => $item) {
            $options[$item['value']] = $item['label'];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toOptionArrayImportExportStatus()
    {
        return [
            ['value' => self::FAILED, 'label' => __('Failed')],
            ['value' => self::SKIPPED, 'label' => __('Skipped')],
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::COMPLETE, 'label' => __('Complete')],
            ['value' => self::PROCESSING, 'label' => __('Processing')]
        ];
    }

    /**
     * @return array
     */
    public function getImportExportStatusOptionsArray()
    {
        $options = [];
        foreach($this->toOptionArrayImportExportStatus() as $index => $item) {
            $options[$item['value']] = $item['label'];
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toOptionArrayExportStatus()
    {
        return [
            ['value' => self::FAILED, 'label' => __('Failed')],
            ['value' => self::PENDING, 'label' => __('Pending')],
            ['value' => self::PROCESSING, 'label' => __('Processing')],
            ['value' => self::CREATED, 'label' => __('Exported')],
            ['value' => self::UPDATED, 'label' => __('Updated')]
        ];
    }

    /**
     * @return array
     */
    public function toOptionHashImportExportStatus()
    {
        $options = [];
        foreach($this->toOptionArrayExportStatus() as $index => $item) {
            $options[$item['value']] = $item['label'];
        }

        return $options;
    }
}
