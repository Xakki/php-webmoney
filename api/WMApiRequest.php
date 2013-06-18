<?php

abstract class WMApiRequest
{
    const AUTH_CLASSIC = 'classic';
    const AUTH_LIGHT = 'light';

    /** @var string */
    protected $_authType;

    /** @var string reqn */
    protected $_requestNumber;

    /** @var array */
    protected $_errors = array();

    /** @var string */
    protected $_xml;

    public function __construct($authType = self::AUTH_CLASSIC)
    {
        $this->_authType = $authType;
        $this->_requestNumber = $this->_generateRequestNumber();
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $validator = new WMApiRequestValidator($this->toArray());
        $this->_errors = $validator->validate($this->_getValidationRules());

        return count($this->_errors) == 0;
    }


    /**
     * @return string
     */
    abstract public function getUrl();

    /**
     * @return string
     */
    abstract public function getXml();

    /**
     * @return string
     */
    abstract public function getResponseClassName();

    /**
     * @param WMRequestSigner $requestSigner
     *
     * @return void
     */
    abstract public function sign(WMRequestSigner $requestSigner);

    /**
     * @return string
     */
    public function getAuthType()
    {
        return $this->_authType;
    }

    /**
     * @return int
     */
    public function getRequestNumber()
    {
        return $this->_requestNumber;
    }

    /**
     * @param int $requestNumber
     */
    public function setRequestNumber($requestNumber)
    {
        $this->_requestNumber = $requestNumber;
    }

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return array
     */
    abstract protected function _getValidationRules();

    /**
     * @param string $name
     * @param string|int|float $value
     */
    protected function _addElementToXml($name, $value)
    {
        if (!empty($value)) {
            $this->_xml .= '<' . $name . '>' . $value . '</' . $name . '>';
        }
    }

    /**
     * @return string
     */
    protected function _generateRequestNumber()
    {
        return str_replace('.', '', microtime(true));
    }
}
