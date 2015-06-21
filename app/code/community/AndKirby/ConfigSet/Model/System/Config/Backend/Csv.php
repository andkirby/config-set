<?php

/**
 * Class AndKirby_ConfigSet_Model_System_Config_Backend_Csv
 *
 * @author andkirby@gmail.com
 */
class AndKirby_ConfigSet_Model_System_Config_Backend_Csv extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    /**
     * Get allowed CSV file type
     *
     * @return array
     */
    protected function _getAllowedExtensions()
    {
        return array('csv');
    }

    /**
     * Set configuration from a CSV file
     *
     * @return $this
     */
    protected function _beforeSave()
    {
        $this->setValue(null);
        //@startSkipCommitHooks
        if (isset($_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'])) {
            $file = $_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'];
        } else {
            return $this;
        }
        //@finishSkipCommitHooks

        try {
            /** @var Mage_Core_Model_Resource_Setup $setup */
            $setup = Mage::getResourceModel('core/setup', 'andKirby_configSet_setup');
            $csv   = new Varien_File_Csv();

            if (!file_exists($file)) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('andKirby_configSet')->__('Uploaded file does not exist.')
                );
                return $this;
            }
            if (!is_readable($file)) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('andKirby_configSet')->__('Uploaded file is not readable.')
                );
                return $this;
            }

            $data = $csv->getData($file);
            foreach ($data as $row) {
                if (isset($row[0]) && isset($row[1])) {
                    $path  = $row[0];
                    $value = $row[1];
                    $setup->setConfigData($path, trim($value));
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('andKirby_configSet')->__('An error occurred on uploading configuration file.')
            );
        }
        return $this;
    }
}
