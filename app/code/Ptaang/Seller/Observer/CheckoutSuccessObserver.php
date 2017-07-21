<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Observer;

/**
 * Class SaveOrderBeforeSalesModelQuoteObserver
 *
 * @package Wage\SuccessPage\Observer
 * Observer create for Fix the issue of Copy data to quote to order
 */
class CheckoutSuccessObserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Ptaang\Seller\Model\Seller\ProductFactory
     */
    protected $_sellerProductFactory;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * SaveOrderSuccessSalesModelQuoteObserver constructor.
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     * @param \Magento\Sales\Model\OrderFactory $order
     */
    public function __construct(
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Magento\Sales\Model\OrderFactory $order
    ){
       $this->_sellerProductFactory = $sellerProductFactory;
       $this->_order = $order;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /** array, for the moment is working with only one Order */
        $orderId = $observer->getEvent()->getData('order_ids');
        if (empty($orderIds) || !is_array($orderIds) || count($orderIds) > 1) {
            return $this;
        }
        /* @var \Magento\Sales\Model\Order $order */
        $order = $this->_order->load(reset($orderId));

        $sellers = [];
        foreach ($order->getAllVisibleItems() as $item){
            /** @var \Magento\Quote\Model\Quote\Item $item */

            $product = $item->getProduct();
            $productId = $product->getId();
            /** Load the Seller Product for increase the qty sold and send notification to the seller */
            $sellerProduct = $this->_sellerProductFactory->create();
            $sellerProduct = $sellerProduct->loadByProductId($productId);
            if($sellerProduct && $sellerProduct->getId()){
                /** Increase the Qty sold */
                $qtySold = $sellerProduct->getData("qty_sold");
                $sellerProduct->setData($qtySold + (int)$item->getQtyOrdered());

                $sellerId = $sellerProduct->getData("seller_id");
                $arrayProductSold = ["sku" => $product->getSku(), "qty" => (int)$item->getQtyOrdered()];
                array_push($sellers[$sellerId], $arrayProductSold);
            }
        }
        /** Send Emails to the seller about their products sold */
        if(count($sellers) > 0){

        }
        return $this;
    }
}
