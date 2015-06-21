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
        try {
            $file = $this->_getUploadedFile();
            if (!$file) {
                return $this;
            }
            $status = $this->_setConfigData(
                $this->_getFileData($file)
            );

            if (false === $status) {
                $this->_getSession()->addNotice(
                    Mage::helper('andKirby_configSet')->__('Configuration file has been imported but with error/s.')
                );
            } elseif (true === $status) {
                $this->_getSession()->addSuccess(
                    Mage::helper('andKirby_configSet')->__('Configuration file has been imported.')
                );
            }
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                $e->getMessage()
            );
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('andKirby_configSet')->__('An error occurred on uploading configuration file.')
            );
        }
        return $this;
    }

    /**
     * Set config data
     *
     * @param array $data
     * @return bool|null    Return status of saving
     */
    protected function _setConfigData($data)
    {
        /** @var Mage_Core_Model_Resource_Setup $setup */
        $setup    = Mage::getResourceModel('core/setup', 'andKirby_configSet_setup');
        $savedAll = $data ? true : null;
        foreach ($data as $row) {
            if (isset($row[0]) && isset($row[1])) {
                $path  = $row[0];
                $value = $row[1];

                list($section) = explode('/', $path);
                if ($this->_isSectionAllowed($section)) {
                    $setup->setConfigData($path, trim($value));
                } else {
                    $savedAll = false;
                    $this->_getSession()->addError(
                        Mage::helper('andKirby_configSet')->__("Path '$path' is not allowed to change. Ignored.")
                    );
                }
            }
        }
        return $savedAll;
    }

    /**
     * Get file data
     *
     * @param string $file
     * @return array
     * @throws \Exception
     */
    protected function _getFileData($file)
    {
        if (!file_exists($file)) {
            throw new Mage_Core_Exception(
                Mage::helper('andKirby_configSet')->__('Uploaded file does not exist.')
            );
        }
        if (!is_readable($file)) {
            throw new Mage_Core_Exception(
                Mage::helper('andKirby_configSet')->__('Uploaded file is not readable.')
            );
        }

        $csv = new Varien_File_Csv();
        return $csv->getData($file);
    }

    /**
     * Check if specified section allowed in ACL
     *
     * @param string $section
     * @return bool
     * @see \Mage_Adminhtml_System_ConfigController::_isSectionAllowed()
     */
    protected function _isSectionAllowed($section)
    {
        try {
            /** @var $session Mage_Admin_Model_Session */
            $session = Mage::getSingleton('admin/session');
            $resourceLookup = "admin/system/config/{$section}";
            if ($session->getData('acl') instanceof Mage_Admin_Model_Acl) {
                $resourceId = $session->getData('acl')->get($resourceLookup)->getResourceId();
                if ($session->isAllowed($resourceId)) {
                    return true;
                }
            }
        } catch (Zend_Acl_Exception $e) {
            /**
             * Actions omitted
             *
             * @see \Mage_Adminhtml_System_ConfigController::_isSectionAllowed()
             */
        } catch (Exception $e) {
            /**
             * Actions omitted
             *
             * @see \Mage_Adminhtml_System_ConfigController::_isSectionAllowed()
             */
        }
        return false;
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

    /**
     * Get uploaded file
     *
     * @return string|null
     */
    protected function _getUploadedFile()
    {
        //@startSkipCommitHooks
        if (isset($_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'])) {
            $file = $_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'];
            return $file;
        }
        //@finishSkipCommitHooks
        return null;
    }
}
