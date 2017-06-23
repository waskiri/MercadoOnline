<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
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
        }

        $setup->endSetup();
    }
}