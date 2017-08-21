<?php
/**
 * Copyright � 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Ptaang\Seller\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Module\Setup\Migration;
use Magento\Customer\Model\GroupFactory;

class UpgradeData implements  UpgradeDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    
    protected $groupFactory;

    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
                            GroupFactory $groupFactory
                            )
    {
        $this->groupFactory = $groupFactory;
    }
    
    public function upgrade(
                            ModuleDataSetupInterface $setup,
                            ModuleContextInterface $context
                            ){
        
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            // Create the new group
            /** @var \Magento\Customer\Model\Group $group */
            $group = $this->groupFactory->create();
            $group
                ->setCode('Seller')
                ->setTaxClassId(3) // magic numbers OK, core installers do it?!
                ->save();
        }else if(version_compare($context->getVersion(), '1.0.3') < 0){
            /** Add the departments of Bolivia */
            /** Country Id BO */
            $connection = $setup->getConnection();
            /** Beni BNI*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'BNI',
                    'default_name' => 'Beni',
                ]
            );
            /** Chuquisaca CHQ*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'CHQ',
                    'default_name' => 'Chuquisaca',
                ]
            );
            /** Cochabamba CBA*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'CBA',
                    'default_name' => 'Cochabamba',
                ]
            );
            /** La Paz LPZ*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'LPZ',
                    'default_name' => 'La Paz',
                ]
            );
            /** Oruro ORU*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'ORU',
                    'default_name' => 'Oruro',
                ]
            );
            /** Potosí PSI*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'PSI',
                    'default_name' => 'Potosí',
                ]
            );

            /** Santa Cruz SCZ*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'SCZ',
                    'default_name' => 'Santa Cruz',
                ]
            );

            /** Tarija SCZ*/
            $connection->insert(
                $setup->getTable('directory_country_region'),
                [
                    'country_id' => 'BO',
                    'code' => 'TJA',
                    'default_name' => 'Tarija',
                ]
            );
        }

        $setup->endSetup();
    }
}