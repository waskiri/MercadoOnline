<?php
/**
 * Copyright � 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ptaang\Seller\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface {
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup,
                                \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            /** Drop the wrong foreign Key */
            $setup->getConnection()->dropForeignKey(
                $setup->getTable('seller_product'),
                $setup->getFkName(
                    "seller_product",
                    'product_id',
                    "product_entity",
                    'entity_id'
                )
            );
            /** Add the new  foreign Key */
            $setup->getConnection()->addForeignKey(
                $setup->getFkName(
                    'seller_product',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                $setup->getTable('seller_product'),
                'product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        }
        $setup->endSetup();
    }
}