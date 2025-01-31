<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Core
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Core\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AbstractData
 * @package Mageplaza\Core\Helper
 */
class Validate extends AbstractData
{
    /**
     * @var array
     */
    protected $configModulePath = [];

    /**
     * @var array
     */
    protected $_mageplazaModules;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * Validate constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        ModuleListInterface $moduleList
    )
    {
        $this->_moduleList = $moduleList;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function needActive($moduleName)
    {
        $type = $this->getModuleType($moduleName);
        if (!$type || !in_array($type, ['1', '2'])) {
            return false;
        }

        return true;
    }

    /**
     * @param $moduleName
     * @return mixed
     */
    public function getModuleType($moduleName)
    {
        $configModulePath = $this->getConfigModulePath($moduleName);

        return $this->getConfigValue($configModulePath . '/module/type');
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function isModuleActive($moduleName)
    {
        $configModulePath = $this->getConfigModulePath($moduleName);

        return $this->getConfigValue($configModulePath . '/module/active')
            && $this->getConfigValue($configModulePath . '/module/product_key');
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function getConfigModulePath($moduleName)
    {
        if (!isset($this->configModulePath[$moduleName])) {
            $this->configModulePath[$moduleName] = false;

            $helperClassName = str_replace('_', '\\', $moduleName) . '\Helper\Data';
            if (class_exists($helperClassName)) {
                $helper = $this->objectManager->get($helperClassName);
                if ($helper instanceof AbstractData) {
                    $this->configModulePath[$moduleName] = $helper::CONFIG_MODULE_PATH;
                }
            }
        }

        return $this->configModulePath[$moduleName];
    }

    /**
     * @return array
     */
    public function getModuleList()
    {
        if (is_null($this->_mageplazaModules)) {
            $this->_mageplazaModules = [];

            $moduleList = $this->_moduleList->getNames();
            foreach ($moduleList as $name) {
                if (strpos($name, 'Mageplaza_') === false) {
                    continue;
                }

                $this->_mageplazaModules[] = $name;
            }
        }

        return $this->_mageplazaModules;
    }
}