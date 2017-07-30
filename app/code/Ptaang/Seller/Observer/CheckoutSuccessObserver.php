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
     * @var \Ptaang\Seller\Model\SendMailByProductSold
     */
    protected $_sendEmailProductSold;

    /**
     * SaveOrderSuccessSalesModelQuoteObserver constructor.
     * @param \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory
     * @param \Ptaang\Seller\Model\SendMailByProductSold $sendEmailProductSold
     * @param \Magento\Sales\Model\OrderFactory $order
     */
    public function __construct(
        \Ptaang\Seller\Model\Seller\ProductFactory $sellerProductFactory,
        \Ptaang\Seller\Model\SendMailByProductSold $sendEmailProductSold,
        \Magento\Sales\Model\OrderFactory $order
    ){
       $this->_sellerProductFactory = $sellerProductFactory;
       $this->_sendEmailProductSold = $sendEmailProductSold;
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
        if (empty($orderId) || !is_array($orderId) || count($orderId) > 1) {
            return $this;
        }
        /* @var \Magento\Sales\Model\Order $order */
        $order = $this->_order->create();
        $order = $order->load(reset($orderId));

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
                $sellerProduct->setData("qty_sold", ($qtySold + (int)$item->getQtyOrdered()));
                $sellerProduct->save();
                $sellerId = $sellerProduct->getData("seller_id");
                $arrayProductSold = [
                    "sku" => $product->getSku(),
                    "qty" => (int)$item->getQtyOrdered(),
                    "product_name" => $product->getName()];
                if(!array_key_exists($sellerId, $sellers)){
                    $sellers[$sellerId] = [];
                }
                array_push($sellers[$sellerId], $arrayProductSold);
            }
        }
        /** Send Emails to the seller about their products sold
         * the key is the sellerId and the array is information about the Products and Qty Sold
         */
        if(count($sellers) > 0){
            foreach ($sellers as $key => $value){
                $this->_sendEmailProductSold->execute($value, $key);
            }
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/sellers.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info(print_r($sellers, true));
        }
        return $this;
    }
}
