<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Product\View;


class SellerInformation extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Ptaang\Seller\Model\Seller\ProductFactory
     */
    protected $_sellerProductFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var \Ptaang\Seller\Model\SellerFactory
     */
    protected $_sellerFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_sellerProductFactory = $sellerProductFactory;
        $this->_customerFactory = $customerFactory;
        $this->_sellerFactory = $sellerFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    /**
     * Get Seller Information
     * @return array
     */
    public function getSellerInformation(){
        $informationSeller = [];
        $product = $this->getProduct();
        if($product && $productId = $product->getId()){
            /** @var $sellerProduct \Ptaang\Seller\Model\Seller\Product  */
            $sellerProduct = $this->_sellerProductFactory->create()->loadByProductId($productId);
            if($sellerProduct && $sellerProduct->getId()){
                $sellerId = $sellerProduct->getData("seller_id");
                $seller = $this->_sellerFactory->create()->load("$sellerId");
                if($seller && $seller->getId()){
                    $customer = $this->_customerFactory->create()->load($seller->getData("customer_id"));
                    if($customer && $customer->getId()){
                        $informationSeller["name"] = $customer->getName();
                        $informationSeller["email"] = $customer->getEmail();
                        $informationSeller["qty_sold"] = $sellerProduct->getData("qty_sold");

                    }
                }
            }

        }
        return $informationSeller;
    }
}
