<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ptaang\Seller\Observer;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Helper\Address as HelperAddress;
use Magento\Customer\Model\Address\AbstractAddress;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State as AppState;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Vat;
use Magestore\Storepickup\Model\Store;

/**
 * Customer Observer Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AfterAddressSaveObserver implements ObserverInterface
{
    private $_storepickup;
    
    protected $request;
    
    protected $_customerGroupCollection;
    
    public function __construct(
        Store $storepickup,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Customer\Model\Group $customerGroupCollection
    ){
       $this->_storepickup =  $storepickup;
       $this->request = $request;
       $this->_customerGroupCollection = $customerGroupCollection;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $customerAddress Address */
        $customerAddress = $observer->getCustomerAddress();
        $customer = $customerAddress->getCustomer();
        
        $collection_group = $this->_customerGroupCollection->load($customer->getGroupId()); 
        
        if($collection_group->getCustomerGroupCode() == 'Seller'){
            if(!$this->request->getParam('id')){
                $this->saveStorepickup($customerAddress, $customer, $this->_storepickup);
            }else{
                $storepickups = $this->_storepickup->getCollection();
                foreach($storepickups as $storepickup){//echo 'ccc: '.$storepickup->getRewriteRequestPath();
                    if($storepickup->getRewriteRequestPath() == 'storepickup-' . $this->request->getParam('id')){
                        $this->editStorepickup($customerAddress, $customer, $storepickup);
                    }
                }
            }
        }
    }
    
    private function saveStorepickup($customerAddress, $customer, $storepickup){
        $street = $customerAddress->getStreet();
                
        $region = $customerAddress->getRegion();
        
        $country_id = $customerAddress->getCountryId();
        
        try {
            
            $response_a = $this->getLocation($street, $region, $country_id);
            
            $latitude = $response_a->results[0]->geometry->location->lat;
            $longitude = $response_a->results[0]->geometry->location->lng;
            
            $data = array();
            
            $data['address'] = $street[0];
            $data['city'] = $customerAddress->getCity();
            $data['country_id'] = $country_id;
            $data['email'] = $customer->getEmail();
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
            $data['phone'] = $customerAddress->getTelephone();
            $data['schedule_id'] = 2;
            $data['state'] = $region;
            $data['status'] = 1;
            $data['store_name'] = $customerAddress->getCompany()?$customerAddress->getCompany():$customer->getName();
            $data['zipcode'] = $customerAddress->getPostcode();
            $data['zoom_level'] = 8;
            $data['owner_name'] = $customer->getName();
            $data['owner_email'] = $customer->getEmail();
            $data['owner_phone'] = $customerAddress->getTelephone();
            $data['rewrite_request_path'] = 'storepickup-' . $customerAddress->getId();
            
            $storepickup->setData($data);
            $storepickup->save();
        }catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
    }
    
    private function editStorepickup($customerAddress, $customer, $storepickup){
        $street = $customerAddress->getStreet();
                
        $region = $customerAddress->getRegion();
        
        $country_id = $customerAddress->getCountryId();
        
        try {
            
            $response_a = $this->getLocation($street, $region, $country_id);
            
            $latitude = $response_a->results[0]->geometry->location->lat;
            $longitude = $response_a->results[0]->geometry->location->lng;
            
            $storepickup->setData('address', $street[0]);
            $storepickup->setData('city', $customerAddress->getCity());
            $storepickup->setData('latitude', $latitude);
            $storepickup->setData('longitude', $longitude);
            $storepickup->setData('phone', $customerAddress->getTelephone());
            $storepickup->setData('state', $region);
            $storepickup->setData('store_name', $customerAddress->getCompany()?$customerAddress->getCompany():$customer->getName());
            $storepickup->setData('zipcode', $customerAddress->getPostcode());
            $storepickup->setData('owner_name', $customer->getName());
            $storepickup->setData('owner_email', $customer->getEmail());
            $storepickup->setData('owner_phone', $customerAddress->getTelephone());
            
            $storepickup->save();
        }catch (Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
    }
    
    private function getLocation($street, $region, $country_id){
        $address = $street[0] . ', ' . $region . ', ' . $country_id;
        $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false&region=India";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }
}
