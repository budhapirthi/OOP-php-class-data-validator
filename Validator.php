<?php
namespace Library\Validation;

/**
 * Class Validator
 * This class takes care of generic validation
 *
 * @version 1.0
 * @author  Bishal Budhapirthi <bishalbudhapirthi@gmail.com>
 * @created 2018-09-05
 */
class Validator
{
    /**
     * @var
     */
    public $arrFieldsMapping;

    /**
     * @var
     */
    public $_arrValidationErrors;

    /**
     * Validator constructor.
     */
    public function __construct(array $arrFieldsMapping)
    {
        $this->arrFieldMapping = $arrFieldsMapping;
    }

    /**
     * Loops through all the attribute names in arrayFieldMapping and validate all the property values by their data type
     *
     * @param array $arrPropertiesToIgnore
     *
     * @return bool
     */
    public function isValid(array $arrPropertiesToIgnore)
    {
        foreach ($this->arrFieldsMapping as $strFieldInputName => $arrProperties){

            // Checks if the required flag is set to true and if the value is provided.
            if (isset($arrProperties['required']) && $arrProperties['required'] === true && $this->$arrProperties['property_name'] === ""
                || $this->$arrProperties['property_name'] === null){
                $this->_arrValidationErrors[$strFieldInputName] = "No value provided for {$strFieldInputName}";

                continue;
            }

            // Checks if the property value is set, if it not , it require no validation
            if (!isset($this->$arrProperties['property_name']) || is_null($this->$arrProperties['property_name'])
                || in_array($arrProperties['property_name'], $arrPropertiesToIgnore)){

                continue;
            }

            switch ($arrProperties['type']) {

                case 'integer' :

                    if (!$this->isInteger($this->$arrProperties['property_name'])){
                        $this->_arrValidationErrors[$strFieldInputName] = "Invalid numeric value provided for {$strFieldInputName}";
                    }
                    break;

                case 'string' :

                    if (!$this->isString($this->$arrProperties['property_name'])){
                        $this->_arrValidationErrors[$strFieldInputName] = "Invalid string value provided for {$strFieldInputName}";
                    }
                    break;
                default :
                    $this->_arrValidationErrors[] = 'An unknown property type was received';
                    continue;
                    break;

            }

            // Validate minimum length
            if (isset($arrProperties['min_length'])){
                if (!$this->isMinLengthValid($this->$arrProperties['property_name'], $arrProperties['min_length'])){
                    $this->_arrValidationErrors[$strFieldInputName] = "{$strFieldInputName} must be at least {$arrProperties['min_length']} character long";
                }

            }

            // Validate maximum length
            if (isset($arrProperties['max_length'])){
                if (!$this->isMaxLengthValid($this->$arrProperties['property_name'], $arrProperties['min_length'])){
                    $this->_arrValidationErrors[$strFieldInputName] = "{$strFieldInputName} exceed maximum character length";
                }
            }

        }

        if ($this->hasError()){
            return false;
        }

        return true;

    }

    /**
     * Check if a string is shorter than a minimum length
     *
     * @param $field            - the Value to check
     * @param $intMinLength     - the number to check against
     *
     * @return bool
     */
    private function isMinLengthValid($field, $intMinLength)
    {
        return strlen($field) < (int)$intMinLength ? false : true;
    }

    /**
     * Check if a string is longer than a maximum length
     *
     * @param $field            - the Value to check
     * @param $intMinLength     - the number to check against
     *
     * @return bool
     */
    private function isMaxLengthValid($field, $intMaxLength)
    {
        return strlen($field) > (int)$intMaxLength ? false : true;
    }

    /**
     * Check if a given value is a valid number (integer)
     *
     * @param $field            - the Value to check
     *
     * @return bool
     */
    private function isInteger($field)
    {
        return (!is_numeric($field) || floor($field) != $field) ? false : true;
    }

    /**
     * Check if a given value is a valid string
     *
     * @param $field            - the Value to check
     *
     * @return bool
     */
    private function isString($field)
    {
        return (!is_string($field)) ? false : true;

    }

    /**
     * Check if a given value contain only numbers and alphabet
     *
     * @param $field            - the Value to check
     *
     * @return bool
     */
    private function isAlphanumeric($field)
    {
        return preg_match('/^([a-zA-Z0-9\-])+$/', $field) ? true : false;

    }

    /**
     * Check if the class has generated any error within loop
     *
     * @return bool
     */
    public function hasError()
    {
        return count($this->_arrValidationErrors) > 0 ? true :false;

    }

    /**
     * Retrieve the error messages :  attributeName => error message
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_arrValidationErrors;
    }

}
