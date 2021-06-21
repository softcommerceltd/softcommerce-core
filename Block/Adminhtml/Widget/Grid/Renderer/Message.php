<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftCommerce\Core\Block\Adminhtml\Widget\Grid\Renderer;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Backend\Block\Context;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Message
 * @package SoftCommerce\Core\Block\Adminhtml\Widget\Grid\Renderer
 */
class Message extends AbstractRenderer
{
    /**
     * @var Json|null
     */
    protected $_serializer;

    /**
     * @var string
     */
    protected $_status;

    /**
     * @var string
     */
    protected $_html;

    /**
     * Message constructor.
     * @param Json|null $serializer
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        ?Json $serializer = null,
        array $data = []
    ) {
        $this->_serializer = $serializer
            ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct($context, $data);
    }

    /**
     * @var array
     */
    protected static $_statuses;

    /**
     * @param DataObject $row
     * @return mixed|string
     */
    public function render(DataObject $row)
    {
        if (!$response = $row->getData($this->getColumn()->getIndex())) {
            return $response;
        }

        try {
            $response = $this->_serializer->unserialize($response ?: '[]');
        } catch (\Exception $e) {
            return '';
        }

        $this->_html = '';
        $this->_status = strtolower($row->getStatus());
        if ($title = $row->getActionCode()) {
            $title = ucwords(str_replace('_', ' ', $title));
            $this->_html .= "<h2>{$title}</h2>";
        }

        $html = '<div class="data-grid-cell-content plenty">';
        $this->_getResponseHtml($response);
        $html .= "<i class=\"far fa-comment status-{$this->_status} tooltip btn\" aria-hidden=\"true\" {$this->_getOptionsJs()}>";
        $html .= "</i>";
        $html .= '</div>';

        return $html;
    }

    /**
     * @param array|string $response
     * @return string
     */
    protected function _getResponseHtml($response)
    {
        if (!is_array($response)) {
            $this->_html .= "<p class=\"{$this->_status}\">{$this->_getHtml($response)}</p>";
        } else {
            foreach ($response as $key => $item) {
                if (!is_array($item) && is_string($key)) {
                    $this->_html .= "<p class=\"status-{$this->_status}\">{$key} => {$this->_getHtml($item)}</p>";
                } elseif (!is_array($item)) {
                    $this->_html .= "<p class=\"status-{$this->_status}\">{$this->_getHtml($item)}</p>";
                } else {
                    $this->_getResponseHtml($item);
                }
            }
        }

        return $this->_html;
    }

    /**
     * @return string
     */
    protected function _getOptionsJs()
    {
        return 'data-mage-init="' . $this->_escaper->escapeHtml(
            $this->_serializer->serialize(
                [
                    'gridRowModalMessageWidget' => [
                        'html' => '<div class="plenty">'.$this->_html.'</div>'
                    ],
                ]
            )
        ) . '"';
    }

    /**
     * @param $string
     * @return string
     */
    protected function _getHtml($string)
    {
        $string = strip_tags($string);
        return $string = strlen($string) > 400 ? mb_substr($string, 0, 400).'...' : $string;
    }
}
