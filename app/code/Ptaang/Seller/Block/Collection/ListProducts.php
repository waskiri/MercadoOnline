<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Collection;


/**
 * Seller List of Products
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class ListProducts extends \Magento\Customer\Block\Account\Dashboard
{


    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Ptaang\Seller\Model\Seller\ProductFactory
     */
    protected $_sellerProductFactory;

    /**
     * @var \Ptaang\Seller\Helper\Data
     */
    protected $_helperSeller;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     * @param \Ptaang\Seller\Helper\Data $helperSeller
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Ptaang\Seller\Helper\Data $helperSeller

    ) {
        $this->_helperSeller = $helperSeller;
        $this->_sellerProductFactory = $sellerProductFactory;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement);
    }

    public function getProducts(){
        $customerId = $this->customerSession->getCustomerId();
        $sellerId = $this->_helperSeller->getSellerId($customerId);

    }

}
