<?php
/**
 * Copyright � 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Collection;


/**
 * Seller List of Products
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class ListProductsSold extends \Magento\Customer\Block\Account\Dashboard
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
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockState;
    
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

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
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockState
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Ptaang\Seller\Helper\Data $helperSeller,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockState,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory

    ) {

        $this->_stockState = $stockState;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helperSeller = $helperSeller;
        $this->_sellerProductFactory = $sellerProductFactory;
        $this->_storeManager = $context->getStoreManager();
        $this->_orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('My Products Sold'));

        if ($this->getProducts()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'seller.product.list.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->getProducts()
            );
            $this->setChild('pager', $pager);
            $this->getProducts()->load();
        }
        return $this;
    }
    
     /**
     * Return the collection of Orders
     * @return array|order items
     */
    public function getOrders(){
        $collection_ordes = $this->_orderCollectionFactory->create()->addAttributeToSelect('*');
        
        /** Get the current Pager */
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        
        $order_items = [];
        
        foreach($collection_ordes as $order){
            foreach($order->getAllItems() as $item){
                foreach($this->getProducts() as $product){
                    if($item->getSku() == $product->getSku()){
                        if(isset($order_items[$product->getSku()])){
                            $aux = $order_items[$product->getSku()];
                            $qty = $aux->getQtyOrdered() + $item->getQtyOrdered() ;
                            $item->setQtyOrdered($qty);
                        }
                        $order_items[$product->getSku()] = $item;
                    }
                }
            }
        }
        
        return $order_items;
    }

    /**
     * Return the collection of Products
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts(){
        $customerId = $this->customerSession->getCustomer()->getId();
        $sellerId = $this->_helperSeller->getSellerId($customerId);
        $products = [];

        if($sellerId != null){
            /** Retrieve the products of the seller  */
            $collectionSellerProducts = $this->_sellerProductFactory->create();
            $collectionSellerProducts = $collectionSellerProducts->getCollection();
            $collectionSellerProducts->addFieldToFilter('seller_id', $sellerId);
            $collectionSellerProducts->addFieldToSelect("product_id");
            $sellerProducts = [];
            foreach ($collectionSellerProducts as $sellerProduct){
                array_push($sellerProducts, $sellerProduct->getData("product_id"));
            }
            if(count($sellerProducts) > 0){
                $collectionProducts = $this->_productCollectionFactory->create();
                $collectionProducts->addAttributeToFilter('entity_id',
                    array("in", $sellerProducts))->addAttributeToSelect("*");
                //$collectionProducts->setPageSize($pageSize);
                //$collectionProducts->setCurPage($page);
                $products = $collectionProducts;
            }
        }
        return $products;
    }

    /** Return the pager of the grid */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
