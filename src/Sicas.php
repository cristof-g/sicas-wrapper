<?php

namespace Sicas;

class Sicas
{
    const WSDL = "http://www.sicasonline.com/SICASOnline/WS_SICASOnline.asmx?WSDL";

    private $client;
    private $password;
    private $user;
    protected $catalog      = "";
    protected $data         = [];

    /**
     * Method Name
     * Default Value  "Procesar String"
     *
     * @var string
     */
    protected $method       = "Procesar_String";

    /**
     * Method Result for the response
     * Default value "Procesar_StringResult"
     *
     * @var string
     */
    protected $methodResult = "Procesar_StringResult";

    /**
     * Properties for config Data
     *
     * @var array
     */
    protected $properties   = [
        'IDCont'                               => "",
        'IDRelDir'                             => "",
        'IDCli'                                => "",
        'IDCia'                                => "",
        'PropertyTypeProcess'                  => "WS_SaveData",
        'PropertyTypeData'                     => "",
        'PropertyWhatMakeExist'                => "WS_UpdateData",
        'PropertyVerifyContact'                => "WS_NombreCompleto",
        'PropertyDireccionOwner'               => "WS_NoStablish",
        'PropertyTypeDataReturn'               => "WS_XML",
        'PropertyTypeDataAgent'                => "WS_ValueNoIdentify",
        'PropertyForceSolicitud'               => false,
        'PropertyAddCoberturasDefaultFromPlan' => false,
        'PropertyGenerateNumSol'               => false,
        'PropertyGeneratePayments'             => false,
        'PropertyWSCobro'                      => "WS_CobroGeneral",
        'PropertyTipoCobro'                    => "Cob_ReciboComplete",
        'PropertyRecibosMultiAnuales'          => "eRecibosNormalesAnuales",
        'IDRecibo'                             => "",
        'IDTarjeta'                            => "",
        'ImportePago'                          => "",
    ];

    /**
     * @param string $user
     * @param string $password
     */
    public function __construct(string $user, string $password)
    {
        $this->initWebServices($user, $password);
    }

    /**
     * Set SICAS Catalog
     *
     * @param string $catalog
     * @return void
     */
    public function setCatalog(string $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * Set record Data
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Set SICAS Properties
     *
     * @param array $properties
     * @return void
     */
    public function setProperties(array $properties)
    {
        $this->properties = array_merge($this->properties, $properties);
    }

    /**
     * Set Type Data
     *
     * @param string $typeData
     * @return void
     */
    public function setTypeData(string $typeData)
    {
        $this->properties['PropertyTypeData'] = $typeData;
    }

    /**
     * Set Method name
     *
     * @param string $method
     * @return void
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Set Method name result
     *
     * @param string $methodResult
     * @return void
     */
    public function setMethodResult(string $methodResult)
    {
        $this->methodResult = $methodResult;
    }

    /**
     * Create a new record
     *
     * @return mixed
     */
    public function create()
    {
        $dataConfig              = new \ArrayObject();
        $dataConfig->oDataString = $this->dataString();
        $dataConfig->oConfigData = (object) $this->properties;
        $dataConfig->oConfigAuth = $this->credentials();

        return $this->callMethod($dataConfig, $this->method, $this->methodResult);
    }

    /**
     * Get Sicas Web Services Functionss
     *
     * @return object
     */
    public function getFunctions()
    {
        return !$this->isCredentialsEmpty() ? $this->client->__getFunctions() : [];
    }

    /**
     * Call Web Services Method
     *
     * @param object $params
     * @param string $method
     * @param string $methodResult
     * @return array
     */
    protected function callMethod(object $params, string $method, string $methodResult)
    {
        $response = $this->getResponse($method, $params);
        $result   = $response->{$methodResult};

        if (is_string($result)) {
            $xml = simplexml_load_string($result);

            return [
                'id'       => (string) $xml->PROCESSDATA->RESPONSENBR,
                'message'  => (string) $xml->PROCESSDATA->MESSAGE,
                'response' => (string) $xml->PROCESSDATA->RESPONSETXT,
            ];
        }

        return [];
    }

    /**
     * Web Services Credentials
     *
     * @return object
     */
    protected function credentials()
    {
        $configAuth           = new \ArrayObject();
        $configAuth->UserName = $this->user;
        $configAuth->Password = $this->password;

        return $configAuth;
    }

    /**
     * Covert data to string
     *
     * @return string
     */
    protected function dataString()
    {
        $dataEncoded = [];

        foreach ($this->data as $key => $value) {
            $dataEncoded[] = $this->dataStringEncode($key, $value);
        }

        return implode(',', $dataEncoded);
    }

    /**
     * Encode fields to string
     *
     * @param string $field
     * @param mixed $fieldValue
     * @return string
     */
    protected function dataStringEncode($field, $fieldValue)
    {
        return $this->catalog . '.' . $field . '|' . $fieldValue;
    }

    /**
     * Get Method Response
     *
     * @param string $method
     * @param object $params
     * @return mixed
     */
    protected function getResponse(string $method, object $params)
    {
        return $this->client->{$method}($params);
    }

    /**
     * Initialize SOAP Client
     *
     * @return void
     */
    protected function initSoapClient()
    {
        $this->client = new \SoapClient(self::WSDL, array('trace' => 1));
    }

    /**
     * Initialize SICAS Web Services
     *
     * @param string $user
     * @param string $password
     * @return void
     */
    protected function initWebServices(string $user, string $password)
    {
        $this->user     = $user;
        $this->password = $password;

        $this->initSoapClient();
    }

    /**
     * Validate if the credentials are emptys
     *
     * @return boolean
     */
    private function isCredentialsEmpty()
    {
        return empty($this->user) && empty($this->password);
    }
}
