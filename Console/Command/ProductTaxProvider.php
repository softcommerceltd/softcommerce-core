<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace SoftCommerce\Core\Console\Command;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Area;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Magento\Framework\DB\Select;
use Magento\Framework\Serialize\Serializer\Json;
use SoftCommerce\Catalog\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProductTaxProvider
 * @package SoftCommerce\Core\Console\Command
 */
class ProductTaxProvider extends AbstractCommand
{
    const COMMAND_NAME = 'softcommerce_core:product_tax_provider';
    const ID_FILTER = 'id';
    const SKU_FILTER = 'sku';

    /**
     * @var Helper\Data
     */
    private $_helper;

    /**
     * @var array
     */
    private $_productEntity;

    /**
     * @var Product\ActionFactory
     */
    private $_productActionFactory;

    /**
     * ProductGlobalSkuGenerator constructor.
     * @param Helper\Data $helper
     * @param State $appState
     * @param Product\ActionFactory $productActionFactory
     * @param ResourceConnection $resourceConnection
     * @param Json|null $serializer
     * @param string|null $name
     */
    public function __construct(
        State $appState,
        Product\ActionFactory $productActionFactory,
        ResourceConnection $resourceConnection,
        ?Json $serializer = null,
        string $name = null
    ) {
        $this->_productActionFactory = $productActionFactory;
        parent::__construct($appState, $resourceConnection, $serializer, $name);
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription('Product tax provider.')
            ->setDefinition([
                new InputOption(
                    self::ID_FILTER,
                    '-i',
                    InputOption::VALUE_REQUIRED,
                    'ID Filter'
                ),
                new InputOption(
                    self::SKU_FILTER,
                    '-s',
                    InputOption::VALUE_REQUIRED,
                    'SKU Filter'
                )
            ]);

        parent::configure();
    }

    public function _test()
    {
        $this->_process();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_executeBefore($output);

        if ($skuFilter = $input->getOption(self::SKU_FILTER)) {
            $skuFilter = explode(',', $skuFilter);
            $this->_initProductEntity(null, $skuFilter);
            $output->writeln('<info>Generating TAX by SKU filter</info>');
        } elseif ($idFilter = $input->getOption(self::ID_FILTER)) {
            $idFilter = explode(',', $idFilter);
            $this->_initProductEntity($idFilter);
            $output->writeln('<info>Generating TAX by ID filter</info>');
        } else {
            $this->_initProductEntity();
            $output->writeln('<info>Generating TAX for all entries.</info>');
        }

        $this->_process();
        $output->writeln('<info>Done.</info>');

        return;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function _executeBefore(OutputInterface $output)
    {
        $this->_appState->setAreaCode(Area::AREA_ADMINHTML);
        $this->_cliOutput = $output;
        $this->_productEntity = [];
        return $this;
    }

    /**
     * @return $this
     */
    private function _process()
    {
        foreach ($this->_getProductEntity() as $item) {
            if (!isset($item['sku'], $item['entity_id'])) {
                continue;
            }

            try {
                $taxClassId = 0;
                $this->_updateAttribute($item['entity_id'], 'tax_class_id', $taxClassId);
                $this->_cliOutput->writeln(sprintf('<info>Success for SKU: %s. Result: %s</info>', $item['sku'], $taxClassId));
            } catch (\Exception $e) {
                $this->_cliOutput->writeln(sprintf('<error>Error for SKU: %s. Result: %s</error>', $item['sku'], $e->getMessage()));
            }
        }
        return $this;
    }

    /**
     * @param null $idSku
     * @return array|mixed
     */
    private function _getProductEntity($idSku = null)
    {
        if (null === $idSku) {
            return $this->_productEntity;
        }

        if (is_int($idSku)) {
            return $this->_productEntity[$idSku] ?? [];
        }

        $result = array_filter($this->_productEntity, function ($data) use ($idSku) {
            return isset($data['sku']) && $data['sku'] == $idSku;
        });

        return current($result) ?: [];
    }

    /**
     * @param null $id
     * @param null $sku
     * @return $this
     */
    private function _initProductEntity($id = null, $sku = null)
    {
        $adapter = $this->getConnection();

        if (null !== $id) {
            $select = $adapter->select()
                ->from(
                    ['main_tb' => $adapter->getTableName('catalog_product_entity')],
                    ['entity_id', 'sku']
                )->where('main_tb.entity_id IN (?)', is_array($id) ? $id : [$id]);
        } elseif (null !== $sku) {
            $select = $adapter->select()
                ->from(
                    ['main_tb' => $adapter->getTableName('catalog_product_entity')],
                    ['entity_id', 'sku']
                )->where('main_tb.sku IN (?)', is_array($sku) ? $sku : [$sku]);
        } else {
            $select = $adapter->select()
                ->from(
                    ['main_tb' => $adapter->getTableName('catalog_product_entity')],
                    ['entity_id', 'sku']
                );
        }

        $select->order('main_tb.entity_id' . ' ' . Select::SQL_ASC);
        $this->_productEntity = $adapter->fetchAssoc($select);

        return $this;
    }

    /**
     * @param $productId
     * @param $attribute
     * @param $attributeValue
     * @return $this
     */
    private function _updateAttribute($productId, $attribute, $attributeValue)
    {
        /** @var Product\Action $productAction */
        $productAction = $this->_productActionFactory->create();
        $productAction->updateAttributes([$productId], [$attribute => $attributeValue], 0);
        return $this;
    }
}
