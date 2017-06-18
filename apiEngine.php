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

        $response = APIConstants::MESSAGE;
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
                    $resultFunctionCall->$response = $exception->getMessage();
                    $resultFunctionCall->$status = APIConstants::ERROR;
                }

            } else {
                $resultFunctionCall->$status = APIConstants::ERROR;
                $resultFunctionCall->$response = 'Error given params';
            }

        } else {
            $resultFunctionCall->$status = APIConstants::ERROR;
            $resultFunctionCall->$response = 'File not found';
        }

        return json_encode($resultFunctionCall);
    }
}

?>