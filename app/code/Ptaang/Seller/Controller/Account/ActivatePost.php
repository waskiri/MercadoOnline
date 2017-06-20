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
        }else{
            //save the information in the seller table
        }
        echo "<pre>";
        print_r($params);
        echo "</pre>";
        return $resultPage;
    }
}
