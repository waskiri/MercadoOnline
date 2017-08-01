<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Framework\View\Element\Html;

class Links extends \Magento\Framework\View\Element\Html\Links {


    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Ptaang\Seller\Helper\Data
     */
    protected $helperSeller;

    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * Links constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Ptaang\Seller\Helper\Data $helperSeller
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Ptaang\Seller\Helper\Data $helperSeller,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        array $data = []
    ){
        $this->groupFactory = $groupFactory;
        $this->helperSeller = $helperSeller;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve child block HTML
     * @Override for restrict the Links for some users which are not sellers
     *
     * @param   string $alias
     * @param   boolean $useCache
     * @return  string
     */
    public function getChildHtml($alias = '', $useCache = true)
    {
        $url = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);

        /** If enter to yes, the customer is on the DashBoard Panel */
        if (strpos($url, \Ptaang\Seller\Constant\Product::XML_PATH_URL_CUSTOMER) !== false ||
                strpos($url, \Ptaang\Seller\Constant\Product::XML_PATH_URL_SELLER) !== false) {

            if(!$this->customerSession->isLoggedIn()){
                return "";
            }
            $layout = $this->getLayout();
            if (!$layout) {
                return '';
            }

            $customer = $this->customerSession->getCustomer();
            $sellerId = $this->helperSeller->getSellerId($customer->getId());
            $groupId = $customer->getGroupId();
            $groupCode = $this->groupFactory->create()->load($groupId)->getCode();

            $name = $this->getNameInLayout();
            $out = '';
            if ($alias) {
                $childName = $layout->getChildName($name, $alias);
                if ($childName) {
                    $out = $layout->renderElement($childName, $useCache);
                }
            } else {
                foreach ($layout->getChildNames($name) as $child) {
                    /** Check if is Seller Link */
                    if(strpos($child, \Ptaang\Seller\Constant\Product::LINKS_SELLER_NAME)!== false){
                        if($sellerId && $sellerId != 0 &&
                            $groupCode == \Ptaang\Seller\Constant\Product::SELLER_GROUP_CODE){
                            /** Add a custom class */
                            $out .= str_replace('<li class="nav item', '<li class="nav item seller ', $layout->renderElement($child, $useCache));
                        }
                    }else{
                        $out .= $layout->renderElement($child, $useCache);
                    }
                }
            }
            return $out;
        } else {
            return parent::getChildChildHtml($alias, $useCache);
        }

    }
}