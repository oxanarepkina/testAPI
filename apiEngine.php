<?php
require_once('apiConstants.php');
require_once('IllegalApiParamsException.php');

class APIEngine
{
    private $apiFunctionName;

    // Factory method to create API Class
    static function getApiEngineByName($apiName)
    {
        require_once 'apiBaseClass.php';
        require_once $apiName . '.php';
        $apiClass = new $apiName();
        return $apiClass;
    }

    /**
     * APIEngine constructor.
     * @param $apiFunctionName
     * of executed method
     */
    function __construct($apiFunctionName)
    {
        $this->apiFunctionName = stripcslashes($apiFunctionName);

    }

    private function createDefaultJson()
    {
        $retObject = json_decode('{}');
        $status = APIConstants::STATUS;
        $retObject->$status = json_decode('');
        return $retObject;
    }

    /**
     * Call specified method of API using reflection
     * @param $apiFunctionParams
     * method to call
     * @return string
     * either successful or failed json string
     */
    function callApiFunction($apiFunctionParams)
    {
        $resultFunctionCall = $this->createDefaultJson();
        $status = APIConstants::STATUS;

        if (file_exists(APIConstants::API_FILE_NAME . '.php')) {
            $apiClass = APIEngine::getApiEngineByName(APIConstants::API_FILE_NAME);
            $apiReflection = new ReflectionClass(APIConstants::API_FILE_NAME);
            $functionName = $this->apiFunctionName;
            $apiReflection->getMethod($functionName);

            if ($apiFunctionParams) {
                try {
                    $apiClass->$functionName($resultFunctionCall, $apiFunctionParams);
                    $resultFunctionCall->$status = APIConstants::SUCCESS;
                } catch (IllegalApiParamsException $exception) {
                    $this->setErrorResponse($resultFunctionCall, $exception->getMessage());
                }

            } else {
                $this->setErrorResponse($resultFunctionCall, 'Error given params');
            }

        } else {
            $this->setErrorResponse($resultFunctionCall, 'File not found');
        }

        return json_encode($resultFunctionCall);
    }

    function setErrorResponse($resultFunctionCall, $error_message){
        $response = APIConstants::MESSAGE;
        $status = APIConstants::STATUS;
        http_response_code(500);
        $resultFunctionCall->$status = APIConstants::ERROR;
        $resultFunctionCall->$response = $error_message;
    }
}

?>