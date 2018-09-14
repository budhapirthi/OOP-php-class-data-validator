<?php

/**
 * Class Product
 *
 * @version 1.0
 * @author  Bishal Budhapirthi <bishal.budhapirthi@gmail.com>
 * @created 2018-09-14
 */
class Product
{
    /**
     * @var
     */
    private $_strProductCode;

    /**
     * @var
     */
    private $_intBarcode;

    /**
     * @var
     */
    private $_strProductDescription;

    /**
     * @var
     */
    private $_flProductPrice;

    /**
     * @var
     */
    private $_dateCreated;

    /**
     * @var
     */
    private $_arrPropertyErrors;

    /**
     * This is error message set when private validation function fails
     * @var
     */
    private $_strErrorMessage = '';

    /**
     * @var
     */
    private $_arrErrors;

    /**
     * @var array
     */
    protected $arrFieldsMapping = [
        'product code'          => ['property_name' => '_strProductCode',           'validator' => 'isProductCodeValid',        'type' => 'string',     'required' => true, "min_length" => 4, "max_length" => 20],
        'barcode'               => ['property_name' => '_strProductDescription',    'validator' => 'isBarcodeValid',            'type' => 'integer',    'required' => true, "min_length" => 4, "max_length" => 20],
        'product description'   => ['property_name' => '_strProductDescription',    'validator' => 'isProductDescriptionValid', 'type' => 'string',     'required' => true, "max_length" => 13                   ],
        'product price'         => ['property_name' => '_flProductPrice',           'validator' => 'isProductPriceValid',       'type' => 'float',      'required' => true                                       ],
        'date created'          => ['property_name' => '_dateCreated',              'validator' => 'isDateCreatedValid',        'type' => 'date',       'required' => true                                       ]
    ];
    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->_intProductId;
    }

    /**
     * @param mixed $intProductId
     *
     * @return Product
     */
    public function setProductId($intProductId)
    {
        $this->_intProductId = (int)$intProductId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductCode()
    {
        return $this->_strProductCode;
    }

    /**
     * @param mixed $strProductCode
     *
     * @return Product
     */
    public function setProductCode($strProductCode)
    {
        $this->_strProductCode = (string)$strProductCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->_intBarcode;
    }

    /**
     * @param mixed $intBarcode
     *
     * @return Product
     */
    public function setBarcode($intBarcode)
    {
        $this->_intBarcode = (int)$intBarcode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductDescription()
    {
        return $this->_strProductDescription;
    }

    /**
     * @param mixed $strProductDescription
     *
     * @return Product
     */
    public function setProductDescription($strProductDescription)
    {
        $this->_strProductDescription = (string)$strProductDescription;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductPrice()
    {
        return $this->_flProductPrice;
    }

    /**
     * @param mixed $flProductPrice
     *
     * @return Product
     */
    public function setProductPrice($flProductPrice)
    {
        $this->_flProductPrice = (float)$flProductPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    /**
     * @param mixed $dateCreated
     *
     * @return Product
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArrErrors()
    {
        return $this->_arrErrors;
    }

    /**
     * Validate data type and goes through each private function created for each attribute name
     *
     * @param \Validator $objValidator
     *
     * @return bool
     */
    private function isValid(Validator $objValidator)
    {
        // Check for generic validation
        if (!$objValidator->isValid($this->arrFieldsMapping)){
            $boolIsValid =  false;
        }

        // Check for specific validation for each attribute if there is any
        if (!$this->validateSpecificProperty()){
            $boolIsValid =  false;
        }

        // Merge both generic errors and specific property errors
        if (isset($boolIsValid)){
            $this->_arrErrors[] = array_merge($this->_arrPropertyErrors, $objValidator->getErrors());
        }
        return count($this->_arrErrors) > 0 ? false : true;
    }

    /**
     * Loop through arrfield mapping and go through each property name and validate each attribute by their validator function,
     * the error message is set for each attribute, if it does not meet the function
     *
     * @return bool
     */
    private function validateSpecificProperty()
    {
        $this->_arrPropertyErrors = [];

        foreach ($this->arrFieldsMapping as $strFieldName => $arrProperties){

            if (isset($this->$arrProperties['property_name'])){

                if (!empty($arrProperties['validator'])){

                    if (!$this->$arrProperties['validator']($this->$arrProperties['property_name'])){
                        $this->_arrPropertyErrors[$strFieldName] = $this->_strErrorMessage;
                    }
                }
            }
        }

        return (count($this->_arrPropertyErrors) > 0 ) ? false :  true;
    }


    /**
     * Check if a string contains numbers letters and dash
     *
     * @param $field
     *
     * @return bool
     */
    private function isProductCodeValid($field)
    {
        if (!preg_match('/^[a-zA-Z0-9-]+$/',$field)){
            $this->_strErrorMessage = 'Product code contain invalid characters';
            return false;
        }

        return true;
    }


    /**
     * Insert new product into product table
     *
     * @param \Validator $$objValidator
     *
     * @return bool         true if data is valid and insert into db successfully OR false
     */
    public function save(Validator $$objValidator)
    {
        if(!$this->isValid()){
            return false;
        }
        $dbConn = new Database($di);

        $strSql  = "INSERT INTO product 
                                (product_code, 
                                 barcode, 
                                 description, 
                                 price, 
                                 date_created) 
                    VALUES      (:product_code, 
                                 :barcode, 
                                 :description, 
                                 :price, 
                                 :date_created) ";

        $arrParameters = ['product_code' => $this->_strProductCode, 
                          'barcode' => $this->_intBarcode, 
                          'description' => $this->_intBarcode, 
                          'price' => $this->_intBarcode,
                          'date_created' => $this->_dateCreated];

        if (!$dbConn->execute($strSql, $arrParameters)){
            $this->_log->logRequest("Unable to insert product code", [], Level::ALERT);
            return false;
        }

        return true;
    }



}
