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

class Newproduct extends \Magento\Customer\Controller\AbstractAccount
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
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $resultPageFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        CustomerRepositoryInterface $customerRepository
    ) {
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


        $customerId = $this->session->getCustomerId();
        $customerDataObject = $this->customerRepository->getById($customerId);
        $this->session->setCustomerData($customerDataObject);

        $resultPage->getConfig()->getTitle()->set(__('New'));
        $resultPage->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        return $resultPage;
    }
}
