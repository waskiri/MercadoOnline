<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Storepickup
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

namespace Magestore\Storepickup\Block\Adminhtml\Store\Edit\Tab;

/**
 * @category Magestore
 * @package  Magestore_Storepickup
 * @module   Storepickup
 * @author   Magestore Developer
 */
class GmapTab extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Config\Model\Config\Source\Locale\Country
     */
    protected $_localCountry;

    /**
     * GmapTab constructor.
     *
     * @param \Magento\Backend\Block\Template\Context            $context
     * @param \Magento\Framework\Registry                        $registry
     * @param \Magento\Framework\Data\FormFactory                $formFactory
     * @param \Magento\Config\Model\Config\Source\Locale\Country $localCountry
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Locale\Country $localCountry,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_localCountry = $localCountry;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->getRegistryModel();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('store_');

        $fieldset = $form->addFieldset('gmap_fieldset', ['legend' => __('Location Information')]);

        if ($model->getId()) {
            $fieldset->addField('storepickup_id', 'hidden', ['name' => 'storepickup_id']);
        }

        $fieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Address'),
                'title' => __('Address'),
                'required' => true,
                'placeholder' => 'Enter your address',
            ]
        );

        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
                'placeholder' => 'City',
            ]
        );

        $fieldset->addField(
            'zipcode',
            'text',
            [
                'name' => 'zipcode',
                'label' => __('Zip Code'),
                'title' => __('Zip Code'),
                'required' => true,
                'placeholder' => 'Zip Code',
            ]
        );

        $fieldset->addField(
            'country_id',
            'select',
            [
                'label' => __('Country'),
                'title' => __('Country'),
                'name' => 'country_id',
                'values' => $this->_localCountry->toOptionArray(),
                'style' => 'width: 100%;',
            ]
        );

        $fieldset->addField(
            'region_updater',
            'note',
            [
                'name' => 'region_updater',
                'label' => __('State/Province'),
                'title' => __('State/Province'),
                'text' => $this->getChildHtml('store_edit_region_updater'),
                'style' => 'width:100%;',
            ]
        );

        $fieldset->addField(
            'latitude',
            'text',
            [
                'name' => 'latitude',
                'label' => __('Latitude'),
                'title' => __('Latitude'),
                'required' => true,
                'readonly' => true,
            ]
        );

        $fieldset->addField(
            'longitude',
            'text',
            [
                'name' => 'longitude',
                'label' => __('Longitude'),
                'title' => __('Longitude'),
                'required' => true,
                'readonly' => true,
            ]
        );

        $fieldset->addField(
            'zoom_level',
            'text',
            [
                'name' => 'zoom_level',
                'label' => __('Zoom Level'),
                'title' => __('Zoom Level'),
                'required' => true,
                'readonly' => true,
            ]
        );

        $fieldset->addField(
            'marker_icon',
            'image',
            [
                'name' => 'marker_icon',
                'label' => __('Marker Icon'),
                'title' => __('Marker Icon'),
                'note' => __('Recommended size: 400x600 px. Supported format: jpg, jpeg, gif, png.'),
            ]
        );

        $mapBlock = $this->getLayout()
            ->createBlock('Magestore\Storepickup\Block\Adminhtml\Store\Edit\Tab\GmapTab\Renderer\Map');

        $fieldset->addField(
            'googlemap',
            'text',
            [
                'label' => __('Store Map'),
                'name' => 'googlemap',
            ]
        )->setRenderer($mapBlock);

        if (!$model->getId()) {
            $model->setLatitude('0.00000000')
                ->setLongitude('0.00000000')
                ->setZoomLevel(4);
        }

        if (is_array($model->getData('marker_icon'))) {
            $model->setData('marker_icon', $model->getData('marker_icon/value'));
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * get registry model.
     *
     * @return \Magestore\Storepickup\Model\Store
     */
    public function getRegistryModel()
    {
        return $this->_coreRegistry->registry('storepickup_store');
    }

    /**
     * Return Tab label.
     *
     * @return string
     *
     * @api
     */
    public function getTabLabel()
    {
        return __('Google Map Location');
    }

    /**
     * Return Tab title.
     *
     * @return string
     *
     * @api
     */
    public function getTabTitle()
    {
        return __('Google Map Location');
    }

    /**
     * Can show tab in tabs.
     *
     * @return bool
     *
     * @api
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden.
     *
     * @return bool
     *
     * @api
     */
    public function isHidden()
    {
        return false;
    }
}
