<?php
/**
 * Copyright © 2013-2017 Raul Encinas, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Ptaang\Seller\Observer;

use Magento\Framework\Event\ObserverInterface;
use Ptaang\Seller\Model\ActiveSeller;

/**
 * Customer Observer Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AfterCustomerSaveObserver implements ObserverInterface
{
    protected $_customerGroupCollection;
    protected $_activeSeller;
    
    public function __construct(
                                    \Magento\Customer\Model\Group $customerGroupCollection,
                                    ActiveSeller $ActiveSeller
                                ){
        $this->_customerGroupCollection = $customerGroupCollection;
        $this->_activeSeller = $ActiveSeller;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer){
        $event = $observer->getEvent();
        $customerData = $event->getCustomer();
        
        $collection_group = $this->_customerGroupCollection->load($customerData->getGroupId()); 
        
        if($collection_group->getCustomerGroupCode() == 'Seller'){
            //send email notification active seller
            $this->_activeSeller->execute($customerData);
        }
    }
}