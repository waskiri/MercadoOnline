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
class NewProduct extends \Magento\Customer\Block\Account\Dashboard
{

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

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
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param array $layoutProcessors
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $layoutProcessors = [],
        array $data = []
    ) {

        $this->_storeManager   = $context->getStoreManager();
        $this->categoryFactory = $categoryFactory;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement);
    }


    /**
     * @return string
     */
    public function getJsLayout(){
        foreach ($this->layoutProcessors as $processor){
            $this->jsLayout = $processor->process($this->jsLayout);
        }
        /** Add the categories for send it to the Ko Component */
        if(isset($this->jsLayout["components"]) && isset($this->jsLayout["components"]["sellernewproduct"])){
            $this->jsLayout["components"]["sellernewproduct"]["categoryList"] = $this->getCategoriesOption();
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Get the categories in an array
     * @return array $categoryOption
     */
    public function getCategoriesOption(){
        $categoryOption = array();
        $categoryCollection = $this->categoryFactory->create()->getCollection()
                                                    ->addAttributeToSelect('*')
                                                    ->setStore($this->_storeManager->getStore());
        foreach ($categoryCollection as $category){
            array_push($categoryOption, array("name" => $category->getName(), "id" => $category->getId()));
        }
        return $categoryOption;
    }
}
