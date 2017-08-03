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
class ListProductsMoreSold extends \Magento\Framework\View\Element\Template
{

    CONST NUMBER_PRODUCTS = 4;

    /**
     * @var \Ptaang\Seller\Model\Seller\ProductFactory
     */
    protected $_sellerProductFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $_imageBuilder;

    /**
     * ListProductsMoreSold constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_sellerProductFactory = $sellerProductFactory;
        $this->_imageBuilder = $imageBuilder;
        parent::__construct($context, $data);
    }


    /**
     * Get the most products Sold
     * @return  \Magento\Catalog\Model\ResourceModel\Product\Collection | array
     */
     public function getItemsMoreSold(){
        $limitProducts = (int)$this->getNumberProducts() > 0 ? (int)$this->getNumberProducts() : self::NUMBER_PRODUCTS;
        /** @var  \Ptaang\Seller\Model\ResourceModel\Seller\Product\Collection $sellerProducts */
        $sellerProducts = $this->_sellerProductFactory->create()->getCollection();
        $sellerProducts->setOrder('qty_sold','ASC');
        if($limitProducts > 0){
            $sellerProducts->setPageSize($limitProducts);
        }
        $productsId = [];
        foreach ($sellerProducts as $sellerProduct){
            array_push($productsId, $sellerProduct->getData("product_id"));
        }
        $products = [];
         if(count($productsId) > 0){

             $products = $this->_productFactory->create()->getCollection();
             $products->addAttributeToFilter("entity_id", array("in", $productsId));
             $products->addAttributeToSelect("*");
         }
        return $products;
     }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = []){

        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

}
