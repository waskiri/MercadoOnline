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
     * MailSoldProduct constructor.
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     */
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Ptaang\Seller\Helper\Data $helperSeller,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->helperSeller = $helperSeller;
        $this->logger = $logger;
    }

    public function execute($sellerData) {

        $report = [
        ];

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($report);

        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->helperSeller->getTemplateEmailSoldProduct())
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID ])
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->helperSeller->getSenderEmailSoldProduct())
            ->addTo([])
            ->getTransport();

        try{
            $transport->sendMessage();
        }catch (\Exception $e){
            $this->logger->error($e->getMessage());
        }

    }

}