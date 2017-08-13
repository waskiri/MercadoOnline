<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Controller\Account;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class ActivatePost extends \Magento\Customer\Controller\AbstractAccount
{
    /** @var CustomerRepositoryInterface  */
    protected $customerRepository;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Ptaang\Seller\Model\SellerFactory
     */
    protected $sellerFactory;
    
    /**
     * @var MessageManager
     */
    protected $_messageManager;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Ptaang\Seller\Model\SellerFactory $sellerFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        CustomerRepositoryInterface $customerRepository,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory

    ) {
        $this->sellerFactory = $sellerFactory;
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerRepository = $customerRepository;
        $this->_messageManager = $context->getMessageManager();
        parent::__construct($context);
    }

    /**
     * Forgot customer account information page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();


        /** Retrieve params of activate seller  */
        $params     = $this->getRequest()->getParams();
        /** Load the Seller given the customerId */
        $customerId = $this->session->getCustomerId();
        $seller     = $this->sellerFactory->create()->loadByCustomerId($customerId);
        if($seller && $seller->getId()){ //The Seller exists
            //redirect before
            $this->_messageManager->addNotice(__('Your nit is already in the validation process'));
        }else{
            //save the information in the seller table
            $seller_params = $params['seller'];
            try{
                $seller->setData(
                            array(
                                'customer_id' => $customerId, 
                                //'phone_mobile' => null, 
                                //'phone_home' => null, 
                                'nit' => $seller_params['nit']
                                )
                            )->save();
                            
                $this->_messageManager->addSuccess(__("Your nit was added to be a seller, you will receive an email to confirm your activation."));
                
                //send email
                
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $activateSeller = $objectManager->create('Ptaang\Seller\Model\ActiveSeller');
                
                $activateSeller->SendEmail($customerId);
                
            }catch (Exception $e){
                $this->_messageManager->addError(__('Unexpected error', $e));
            }
            
            
        }
        //echo "<pre>";
        //print_r($params);
        //echo "</pre>";
        //return $resultPage;
        $this->_redirect('customer/account');
    }
}
