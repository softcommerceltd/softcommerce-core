<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftCommerce\Core\Block\Adminhtml\Widget\Grid\Renderer;

use Magento\Framework\DataObject;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use SoftCommerce\Core\Model\Source\Status as StatusSource;

/**
 * Class Status
 * @package Plenty\Core\Block\Adminhtml\Widget\Grid\Renderer
 */
class Status extends AbstractRenderer
{
    /**
     * @var StatusSource
     */
    protected $_statusSource;

    /**
     * Status constructor.
     * @param Context $context
     * @param StatusSource $statusSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        StatusSource $statusSource,
        array $data = []
    ) {
        $this->_statusSource = $statusSource;
        parent::__construct($context, $data);
    }

    /**
     * @var array
     */
    protected static $_statuses;

    /**
     * Constructor for Grid Renderer Status
     *
     * @return void
     */
    protected function _construct()
    {
        self::$_statuses = array_merge(
            [null => null],
            $this->_statusSource->toOptionHashScheduleHistoryStatuses()
        );

        parent::_construct();
    }

    /**
     * @param DataObject $row
     * @return \Magento\Framework\Phrase|string
     */
    public function render(DataObject $row)
    {
        if (!$status = $row->getData($this->getColumn()->getIndex())) {
            return __('Could not retrieve status.');
        }

        return __($this->_getStatus($status));
    }

    /**
     * @param $status
     * @return string
     */
    private static function _getStatus($status)
    {
        $html = '<div class="data-grid-cell-content plenty">';
        $description = ucfirst($status);
        $class = '';

        switch ($status) {
            case StatusSource::PENDING :
                $class = 'far fa-clock';
                break;
            case StatusSource::ERROR :
            case StatusSource::FAILED :
                $class = 'fas fa-exclamation-circle';
                break;
            case StatusSource::WARNING :
            case StatusSource::COMPLETE :
            case StatusSource::SUCCESS :
                $class = 'fas fa-check';
                break;
            case StatusSource::SKIPPED :
                $class = 'fas fa-circle-notch';
                break;
            case StatusSource::STOPPED :
                $class = 'fas fa-ban';
                break;
            case StatusSource::PROCESSING :
            case StatusSource::RUNNING :
                $class = 'fas fa-circle-notch fa-spin';
                break;
            case StatusSource::UPDATED :
                $class = 'fas fa-sync-alt';
                break;
            case StatusSource::MISSED :
                $class = 'fas fa-exclamation-triangle';
                break;
        }

        if ($status == StatusSource::PROCESSING) {
            $html .= "<i class=\"{$class} status-{$status}\" aria-hidden=\"true\"></i>";
        } else {
            $html .= "<i class=\"{$class} status-{$status} tooltip\" aria-hidden=\"true\"><span class=\"tooltip-content\">{$description}</span></i>";
        }
        $html .= '</div>';

        return $html;
    }
}
