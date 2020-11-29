<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\Core\Console\Command;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Filter\RemoveAccents;
use Magento\Framework\Serialize\Serializer\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package SoftCommerce\Core\Console\Command
 */
class AbstractCommand extends Command
{
    /**
     * @var State
     */
    protected $_appState;

    /**
     * @var OutputInterface|null
     */
    protected $_cliOutput;

    /**
     * @var ResourceConnection
     */
    private $_resource;

    /**
     * @var AdapterInterface
     */
    protected $_resourceConnection;

    /**
     * @var array
     */
    protected $_error;

    /**
     * @var array
     */
    protected $_response;

    /**
     * @var array
     */
    protected $_request;

    /**
     * @var RemoveAccents
     */
    protected $_filterAccent;

    /**
     * @var Json
     */
    protected $_serializer;

    /**
     * AbstractCommand constructor.
     * @param State $appState
     * @param ResourceConnection $resourceConnection
     * @param RemoveAccents $filterAccent
     * @param Json|null $serializer
     * @param string|null $name
     */
    public function __construct(
        State $appState,
        ResourceConnection $resourceConnection,
        ?Json $serializer = null,
        string $name = null
    ) {
        $this->_appState = $appState;
        $this->_resource = $resourceConnection;
        $this->_serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        parent::__construct($name);
    }

    /**
     * @param null $connectionType
     * @return AdapterInterface
     */
    protected function getConnection($connectionType = null)
    {
        if (null === $this->_resourceConnection) {
            $this->_resourceConnection = $this->_resource
                ->getConnection($connectionType ?: ResourceConnection::DEFAULT_CONNECTION);
        }
        return $this->_resourceConnection;
    }

    /**
     * @return array
     */
    protected function getError()
    {
        return $this->_error;
    }

    /**
     * @return array
     */
    protected function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return array
     */
    protected function getResponse()
    {
        return $this->_response;
    }
}
