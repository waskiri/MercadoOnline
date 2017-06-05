<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create the table 'seller_entity'
         */
        $tableSeller = $setup->getConnection()->newTable(
            $setup->getTable('seller_entity')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Customer ID'
        )->addColumn(
            'phone_mobile',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            15,
            ['nullable' => false],
            'Mobile Phone Number'
        )->addColumn(
            'phone_home',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            15,
            ['nullable' => false],
            'Home Phone Number'
        )->addColumn(
            'nit',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            30,
            ['nullable' => false],
            'NIT'
        )->addForeignKey(
            $setup->getFkName(
                'seller_entity',
                'customer_id',
                'customer_entity',
                'entity_id'
            ),
            'customer_id',
            $setup->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addIndex(
            $setup->getIdxName(
                'seller_entity',
                ['entity_id', 'customer_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['entity_id', 'customer_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->setComment(
            'Seller'
        );
        $setup->getConnection()->createTable($tableSeller);

        /** Create the Relation seller_product */
        $tableSellerProduct = $setup->getConnection()->newTable(
            $setup->getTable('seller_product')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Seller ID'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Product ID'
        )->addColumn(
            'qty_sold',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => '0'],
            'Quantity Sold'
        )->addForeignKey(
            $setup->getFkName(
                'seller_product',
                'seller_id',
                'seller_entity',
                'entity_id'
            ),
            'seller_id',
            $setup->getTable('seller_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                'seller_product',
                'product_id',
                'product_entity',
                'entity_id'
            ),
            'product_id',
            $setup->getTable('product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Seller Product'
        );
        $setup->getConnection()->createTable($tableSellerProduct);

        $setup->endSetup();
    }
}
