<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Form;


/**
 * Seller new product form block
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class ActivateSeller extends \Magento\Customer\Block\Account\Dashboard
{


    /**
     * @var array config
     */
    protected $layoutProcessors;



    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        array $layoutProcessors = [],
        array $data = []
    ) {

        $this->_storeManager = $context->getStoreManager();
        $this->layoutProcessors = $layoutProcessors;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement);
    }



}
