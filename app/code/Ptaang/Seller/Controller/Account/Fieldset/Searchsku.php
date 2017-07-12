<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Controller\Account\Fieldset;


class Searchsku extends \Magento\Framework\App\Action\Action {

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
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;


    /**
     * Search Sku constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionAttributeFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository){

        $this->_productRepository = $productRepository;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory  = $resultRawFactory;
        $this->_helper            = $helper;

        return parent::__construct($context);
    }
    /**
    */
    public function execute(){
        $attributeRequest = null;
        $httpBadRequestCode = 400;

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->_resultRawFactory->create();

        try{
            $attributeRequest = $this->_helper->jsonDecode($this->getRequest()->getContent());
        } catch(\Exception $e){
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }

        if (!$attributeRequest || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
            return $resultRaw->setHttpResponseCode($httpBadRequestCode);
        }
        $response = [];
        $response["found"] = false;
        $response["message"] = "";
        $sku = $attributeRequest["sku"];
        /** If throw an error the product does not exist  */
        try{
            $product = $this->_productRepository->get($sku);
            $response["message"] = __('the sku "%1" is not available', $sku);
            $response["found"] = true;
        }catch (\Exception $e){
            $response["found"] = false;
        }

        $result = $this->_resultJsonFactory->create();
        return $result->setData($response);
    }


}