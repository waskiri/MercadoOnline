<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Controller\Account;


class Saveproduct extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Ptaang\Seller\Model\Seller\Product
     */
    protected $_sellerProductFactory;

    /**
     * Saveproduct constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Json\Helper\Data $helper,
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory){

        $this->_sellerProductFactory = $sellerProductFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory  = $resultRawFactory;
        $this->_helper            = $helper;

        return parent::__construct($context);
    }
    /**
    */
    public function execute(){
        $productInformation = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->_resultRawFactory->create();

        try{
            $productInformation = $this->_helper->jsonDecode($this->getRequest()->getContent());
        } catch(\Exception $e){
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (!$productInformation || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        $response = [];
        $response["error"] = true;
        $response["message"] = "";
        if(isset($productInformation["productId"]) && isset($productInformation["sellerId"])){
            $productId = (int)$productInformation["productId"];
            $sellerId =  (int)$productInformation["sellerId"];
            $sellerProductEntity = $this->_sellerProductFactory->create();
            $sellerProductEntity->loadBySellerProduct($sellerId, $productId);

            if(!$sellerProductEntity->getId()){
                $sellerProductEntity->setData('seller_id', $sellerId);
                $sellerProductEntity->setData('product_id', $productId);
                $sellerProductEntity->save();
                $response["error"] = false;
                $response["message"] = __("Save the product successfully.");
            }else{
                $response["message"] = __("Edit the product successfully.");
            }
        }else{
            $response["message"] = __("Exist a problem ");
        }

        $result = $this->_resultJsonFactory->create();
        return $result->setData($response);
    }


}