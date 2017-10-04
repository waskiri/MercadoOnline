<?php
/**
 * Copyright © 2017 Ptaang, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ptaang\Seller\Block\Framework\View\Element\Html;

class Links extends \Magento\Framework\View\Element\Html\Links {


    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Ptaang\Seller\Helper\Data
     */
    protected $helperSeller;

    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * Links constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Ptaang\Seller\Helper\Data $helperSeller
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ptaang\Seller\Helper\Data $helperSeller,
        array $data = []
    ){
        $this->helperSeller = $helperSeller;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve child block HTML
     * @Override for restrict the Links for some users which are not sellers
     *
     * @param   string $alias
     * @param   boolean $useCache
     * @return  string
     */
    public function getChildHtml($alias = '', $useCache = true)
    {

        /** If enter to yes, the customer is on the DashBoard Panel */
        $layout = $this->getLayout();
        if (!$layout) {
            return '';
        }

        $isSeller = $this->helperSeller->isSeller();
        $groupCode = $this->helperSeller->getGroupName();

        $name = $this->getNameInLayout();
        $out = '';
        if ($alias) {
            $childName = $layout->getChildName($name, $alias);
            if ($childName) {
                $out = $layout->renderElement($childName, $useCache);
            }
        } else {
            foreach ($layout->getChildNames($name) as $child) {
                /** Check if is Seller Link */
                if(strpos($child, \Ptaang\Seller\Constant\Product::LINKS_SELLER_NAME)!== false ){
                    if(($isSeller &&
                            $groupCode == \Ptaang\Seller\Constant\Product::SELLER_GROUP_CODE) ||
                        strpos($child, \Ptaang\Seller\Constant\Product::LINKS_SELLER_EXCEPTION) !== false){
                        /** Add a custom class */
                        $out .= str_replace('<li class="nav item', '<li class="nav item seller ', $layout->renderElement($child, $useCache));
                    }
                }else{
                    $out .= $layout->renderElement($child, $useCache);
                }
            }
        }
        return $out;

    }

    /**
     * Override for modified the link visibility
     */
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {

        if (false != $this->getTemplate()) {
            if (!$this->getTemplate()) {
                return '';
            }
            return $this->fetchView($this->getTemplateFile());
        }
        $isSeller = $this->helperSeller->isSeller();
        $groupCode = $this->helperSeller->getGroupName();
        $html = '';
        if ($this->getLinks()) {
            $html = '<ul' . ($this->hasCssClass() ? ' class="' . $this->escapeHtml(
                        $this->getCssClass()
                    ) . '"' : '') . '>';
            foreach ($this->getLinks() as $link) {
                $url = $link->getHref();
                if($isSeller && ($groupCode == \Ptaang\Seller\Constant\Product::SELLER_GROUP_CODE)){
                    if(strpos($url, \Ptaang\Seller\Constant\Product::LINKS_SELLER_NAME) !== false){
                        if(!strpos($url, \Ptaang\Seller\Constant\Product::LINKS_SELLER_EXCEPTION)){
                            $html .= str_replace('<li class="nav item', '<li class="nav item seller ',
                                                $this->renderLink($link));
                        }
                        
                    }else{
                        $html .= $this->renderLink($link);
                    }
                }else{
                    //echo $url;
                    if(strpos($url, \Ptaang\Seller\Constant\Product::LINKS_SELLER_EXCEPTION) !== false){
                        $html .= str_replace('<li class="nav item', '<li class="nav item seller ',
                                             $this->renderLink($link));
                    }elseif (strpos($url, \Ptaang\Seller\Constant\Product::LINKS_SELLER_NAME) !== false){
                        continue;
                    }else{
                        $html .= $this->renderLink($link);
                    }
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }
}