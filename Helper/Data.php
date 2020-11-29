<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\Core\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package SoftCommerce\Core\Helper
 */
class Data extends AbstractHelper
{
    /**
     * Config path to UE country list
     */
    const XML_PATH_EU_COUNTRIES_LIST = 'general/country/eu_countries';

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    protected function _getStore()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param $path
     * @param null $store
     * @return mixed
     * @throws NoSuchEntityException
     */
    protected function _getConfig($path, $store = null)
    {
        if (null === $store) {
            $store = $this->_getStore();
        }

        return $this->scopeConfig
            ->getValue($path, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @param null $storeId
     * @return false|string[]
     */
    public function getListEuCountries($storeId = null)
    {
        return explode(',', $this->scopeConfig
            ->getValue(self::XML_PATH_EU_COUNTRIES_LIST, ScopeInterface::SCOPE_STORE, $storeId)
        );
    }
}
