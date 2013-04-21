<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2012 ImpressPages LTD.
 * @license see ip_license.html
 */

namespace Modules\developer\form\Field;


class File extends Field
{
    
    public function __construct($options)
    {
        parent::__construct($options);
    }
    
    public function render($doctype)
    {
        $data = array (
            'attributesStr' => $this->getAttributesStr($doctype),
            'classes' => implode(' ',$this->getClasses()),
            'inputName' => $this->getName()
        );

        $view = \Ip\View::create('../view/field/File.php', $data);

        return $view->render();

        //<input type="file"  name="'.htmlspecialchars($this->getName()).'" '.$this->getValidationAttributesStr($doctype).' value="'.htmlspecialchars($this->getDefaultValue()).'" />';
    }
    
    /**
    * CSS class that should be applied to surrounding element of this field. By default empty. Extending classes should specify their value.
    */
    public function getTypeClass()
    {
        return 'file';
    }


    /**
     * @param array $values all posted form values
     * @param string $valueKey this field name
     * @return string
     */
    public function getValueAsString($values, $valueKey)
    {
        if (isset($values[$valueKey]['file']) && is_array($values[$valueKey]['file'])) {
            return implode(', ',$values[$valueKey]['file']);
        } else {
            return '';
        }
    }


    /**
     *
     * Validate if field passes validation
     *
     */
    /**
     * Validate field
     * @param array $data usually array of string. But some elements could be null or even array (eg. password confirmation field, or multiple file upload field)
     * @param string $valueKey This value key could not exist in values array.
     * @return string return string on error or false on success
     */
    public function validate($values, $valueKey)
    {
        if (isset($values[$valueKey]['file']) && is_array($values[$valueKey]['file'])) {
            foreach($values[$valueKey]['file'] as $key => $file) {
                $uploadModel = \Modules\administrator\repository\UploadModel::instance();
                if (!$uploadModel->isFileUploadedByCurrentUser($file)) {
                    return 'Session has ended. Please remove and re-upload files';
                }
            }
        }
        return parent::validate($values, $valueKey);
    }


    /**
     * @param array $values all posted form values
     * @param string $valueKey this field name
     * @return Helper\UploadedFile[]
     */
    public function getFiles($values, $valueKey)
    {
        if (isset($values[$valueKey]['file']) && is_array($values[$valueKey]['file'])) {
            $answer = array();
            foreach($values[$valueKey]['file'] as $key => $file) {
                $originalFileName = $file;
                if (isset($values[$valueKey]['originalFileName'][$key]) && is_string($values[$valueKey]['originalFileName'][$key])) {
                    $originalFileName = $values[$valueKey]['originalFileName'][$key];
                }

                $uploadModel = \Modules\administrator\repository\UploadModel::instance();
                if (!$uploadModel->isFileUploadedByCurrentUser($file)) {
                    throw new \Exception("Security risk. Current user doesn't seem to have uploaded this file");
                }
                $uploadedFile = new Helper\UploadedFile($uploadModel->getTargetDir().$file, $originalFileName);
                $answer[] = $uploadedFile;
            }
            return $answer;
        } else {
            return array();
        }
    }
}