<?php

/**
 * Class AndKirby_ConfigSet_Model_System_Config_Backend_Csv
 *
 * @author andkirby@gmail.com
 */
class AndKirby_ConfigSet_Model_System_Config_Backend_ShowPath extends Mage_Core_Model_Config_Data
{
    /**
     * Set value from session
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->setValue(
            (int)$this->_getSession()->getShowSystemConfigPath()
        );
        parent::_afterLoad();
        return $this;
    }

    /**
     * Set config value into session
     *
     * @return $this
     */
    protected function _beforeSave()
    {
        $this->_getSession()->setShowSystemConfigPath((bool) $this->getValue());
        $this->setValue(null);
        return $this;
    }

    /**
     * Get session model
     *
     * @return \Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
