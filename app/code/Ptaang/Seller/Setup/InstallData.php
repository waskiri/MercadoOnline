<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface {


    /**
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\Set
     */
    private $attributeSetFactory;

    /**
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     * @param \Magento\Eav\Model\Entity\Attribute\Set $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(Customer::ENTITY, 'phone_number', [
            'type' => 'int',
            'label' => 'Phone number',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1000,
            'position' => 1000,
            'system' => 0,
        ]);
        $customerSetup->addAttribute(Customer::ENTITY, 'lastname_mother', [
            'type' => 'varchar',
            'label' => 'Lastname Mother',
            'input' => 'text',
            'required' => false,
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 1001,
            'position' => 1001,
            'system' => 0,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'lastname_mother')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer, edit_form', 'customer_address_edit', 'customer_register_address'],
            ]);

        $attribute->save();

        $attribute2 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'phone_number')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer, edit_form', 'customer_address_edit', 'customer_register_address'],
            ]);

        $attribute2->save();

    }
}