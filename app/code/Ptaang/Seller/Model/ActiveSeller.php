<?php
/**
 * Copyright © 2017 Ptang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Ptaang\Seller\Model;

use Magento\Framework\Mail\Template\TransportBuilder;

class ActiveSeller {
    
    protected $_scopeConfig;
    protected $_storeManager;
    protected $_logger;

    public function __construct(
        TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->_scopeConfig  = $scopeConfig;
        $this->_storeManager = $storeManagerInterface;
        $this->_logger = $logger;
    }

    public function execute($customerData) {
        $report = [
            'name' => $customerData->getName(),
            'email' => $customerData->getEmail()
        ];

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($report);

        $transport = $this->transportBuilder
            ->setTemplateIdentifier('activate_seller_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars(['data' => $postObject])
            ->setFrom(['name' => $this->getConfig('trans_email/ident_general/name'),'email' => $this->getConfig('trans_email/ident_general/email')])
            ->addTo([$customerData->getEmail()])
            ->getTransport();
        try{
            $transport->sendMessage();
        } catch (\Exception $exception){
            $this->_logger->error($exception->getMessage());
        }

    }
    
    public function getConfig($path){
        return $this->_scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()
        );
    }
}