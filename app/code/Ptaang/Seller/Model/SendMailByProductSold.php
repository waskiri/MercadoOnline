<?php
/**
 * Copyright © 2017 Ptang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Ptaang\Seller\Model;



class SendMailByProductSold {

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Ptaang\Seller\Helper\Data
     */
    protected $helperSeller;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var SellerFactory
     */
    protected $sellerFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;
    /**
     * SendMailByProductSold constructor.
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Ptaang\Seller\Helper\Data $helperSeller
     * @param \Ptaang\Seller\Model\SellerFactory $sellerFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Ptaang\Seller\Helper\Data $helperSeller,
        \Ptaang\Seller\Model\SellerFactory $sellerFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->helperSeller = $helperSeller;
        $this->logger = $logger;
        $this->sellerFactory = $sellerFactory;
        $this->customerFactory = $customerFactory;
    }

    public function execute($sellerData, $sellerId) {
        $seller = $this->sellerFactory->create()->load($sellerId);

        $customer = null;
        if($seller && $seller->getId()){
            $customer = $this->customerFactory->create()->load($seller->getCustomerId());
        }
        if($customer == null){
            return;
        }
        $report = [
            "customer_email" => $customer->getEmail(),
            "name" => $customer->getName(),
            "products" => $sellerData
        ];


        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->helperSeller->getTemplateEmailSoldProduct())
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID ])
            ->setTemplateVars($report)
            ->setFrom($this->helperSeller->getSenderEmailSoldProduct())
            ->addTo([$customer->getEmail()])
            ->getTransport();

        try{
            $transport->sendMessage();
        }catch (\Exception $e){
            $this->logger->error($e->getMessage());
        }

    }

}