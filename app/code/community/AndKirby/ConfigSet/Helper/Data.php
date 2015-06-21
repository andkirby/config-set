<?php
/**
 * Class of default helper
 *
 * @author andkirby@gmail.com
 */
class AndKirby_ConfigSet_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get flag of showing system config xpath to values
     *
     * @return bool
     */
    public function isShowSystemConfigPath()
    {
        return (bool)Mage::getSingleton('adminhtml/session')->getShowSystemConfigPath();
    }
}
