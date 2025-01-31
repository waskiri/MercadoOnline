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
     * Attribute for Save the attributes in the default set
     * @var array
     */
    protected $attributesDefault = array();

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
     * @var \Magento\Eav\Model\Entity\Attribute\SetFactory
     */
    protected $_attributeSetFactory;


    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionAttributeFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockState;

    /**
     * @var  \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

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
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     * @param \Ptaang\Seller\Helper\Data $helperSeller
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
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Ptaang\Seller\Helper\Data $helperSeller,
        array $layoutProcessors = [],
        array $data = []
    ) {
        $this->_stockState = $stockState;
        $this->_productFactory = $productFactory;
        $this->_helperSeller = $helperSeller;
        $this->_attributeSetFactory = $attributeSetFactory;
        $this->_storeManager = $context->getStoreManager();
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
        /** Add the categories, attributes for send it to the Ko Component */
        if(isset($this->jsLayout["components"]) && isset($this->jsLayout["components"]["sellernewproduct"])){

            /** Categories */
            $this->jsLayout["components"]["sellernewproduct"]["categoryList"] = $this->getCategoriesOption();

            /** Attributes Set Id */
            $attributeSetIds = $this->getAttributeSetIdArray();
            $this->jsLayout["components"]["sellernewproduct"]["productTypes"] = $attributeSetIds;

            /** Populate the fields if is editing the product */
            if($this->isEditingTheProduct()){
                $product = $this->getProduct();
                $categoryIds = $product->getCategoryIds();
                if(count($categoryIds) > 0){
                    $this->jsLayout["components"]["sellernewproduct"]["categorySelected"] = implode("," , $categoryIds);
                    $this->jsLayout["components"]["sellernewproduct"]["categoryArraySelected"] = $categoryIds;
                }
                $this->jsLayout["components"]["sellernewproduct"]["imagesFile"] = $this->getGalleryProduct($product);


            }
        }
        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Get token of the module
     * @return string
     */
    public function getToken(){
        return $this->_helperSeller->getToken();
    }

    /**
     * Get the sellerId, given the customer Id
     * @return int
     */
    public function getSellerId(){
        return $this->_helperSeller->getSellerId($this->customerSession->getCustomer()->getId());
    }
    /**
     * Get the current website code
     * @return string
     */
    public function getCurrentWebsiteCode(){
        return $this->_storeManager->getWebsite()->getCode();
    }

    /**
     * Return the attribute information in an array
     * @return array
     */
    public function getAttributeSetIdArray(){
        $attributeSetId = [];
        $collectionAttributeSet = $this->_attributeSetFactory->create()->getCollection();
        $collectionAttributeSet->addFieldToFilter("entity_type_id", \Ptaang\Seller\Constant\Product::ATTRIBUTE_SET_ID);
        foreach ($collectionAttributeSet as $attributeSet){
            $attributeSetId[] = ["attribute_set_id" => $attributeSet->getAttributeSetId(),
                "attribute_set_name" =>$attributeSet->getAttributeSetName()];
        }
        return $attributeSetId;
    }


    /**
     * Get the categories in an array
     * @return array $categoryOption
     */
    public function getCategoriesOption(){
        $categoryOption = array();
        $categoryCollection = $this->categoryFactory->create()->getCollection()
                                                    ->addAttributeToSelect("name")
                                                    ->setStore($this->_storeManager->getStore());
        foreach ($categoryCollection as $category){
            array_push($categoryOption, array("name" => $category->getName(), "id" => $category->getId()));
        }
        return $categoryOption;
    }

    /**
     * Check if is editing the product or creating a new one
     * @return boolean
     */
    public function isEditingTheProduct(){
        $editingProduct = false;
        $productId = $this->getRequest()->getParam("product-id");
        if($productId !== null){
            $editingProduct = true;
        }
        return $editingProduct;
    }

    /**
     * Get Product
     * @return \Magento\Catalog\Model\Product | null
     */
    public function getProduct(){
        $product = null;
        $productId = $this->getRequest()->getParam("product-id");
        if($productId !== null){
            $product = $this->_productFactory->create()->load($productId);
        }
        return $product;
    }

    /**
     * Get stock Qty
     * @param int $productId
     * @return int
     */
    public function getStockProduct($productId){
        return $this->_stockState->getStockQty($productId);
    }


    /**
     * Get Custom Attributes given an attribute Set id
     * @param int $productSetId
     * @return array
     */
    public function getCustomAttributes($productSetId){
        return $this->_helperSeller->getAttributesGivenAttributeSetId($productSetId);
    }


    /**
     * Get Gallery given a product
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
     public function getGalleryProduct($product){
         $galleryImages = [];
         $mediaUrl =$this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product';
         $galleryCollection = $product->getMediaGalleryEntries();
         foreach ($galleryCollection as $galleryEntry){
             $galleryName = explode("/", $galleryEntry->getFile());
             $galleryName = $galleryName[count($galleryName) - 1];
             $galleryImages[] = array (
                 "path" => $galleryEntry->getFile(),
                 "src"  => $mediaUrl.$galleryEntry->getFile(),
                 "entry_id" => $galleryEntry->getId(),
                 "name" => $galleryName
             );
         }
         return $galleryImages;
     }
}
