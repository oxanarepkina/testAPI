<?php

class apitest extends apiBaseClass
{

    private function validateNameOrThrowException($name)
    {
        $error_msg = array();

        if (strlen($name) <= 2)
            array_push($error_msg, 'Name is too short');
        elseif (strlen($name) >= 15)
            array_push($error_msg, 'Name is too long');

        if (preg_match("/([a-z])\\1/i", $name) > 0) {
            array_push($error_msg, 'Please enter valid name');
        }

        if (!empty($error_msg)) {
            throw new IllegalApiParamsException(implode(', ', $error_msg));
        }
    }

    function sayHello($jsonObj, $params)
    {
        if (!isset($params) || empty($params['name'])) {
            throw new IllegalApiParamsException("Name parameter doesn't exist");
        }
        $name = $params['name'];

        $this->validateNameOrThrowException($name);

        $response = APIConstants::MESSAGE;
        $jsonObj->$response = 'Hello ' . $name;
    }

    function sayHelloInLanguage($jsonObj, $params)
    {
        if (!isset($params) || empty($params['name']) || empty($params['language'])) {
            throw new IllegalApiParamsException("Invalid parameters");
        }

        $name = $params['name'];
        $language = $params['language'];

        $greet = "Hello";

        $this->validateNameOrThrowException($name);

        if (APIConstants::GOOGLE_API_KEY == '')
            throw new IllegalApiParamsException("Input your Google API Key into file apiConstants.php");

        $url = 'https://www.googleapis.com/language/translate/v2?key=' . APIConstants::GOOGLE_API_KEY . '&q=' . rawurlencode($greet) . '&source=en&target=' . $language;

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        $responseDecoded = json_decode($response, true);
        curl_close($handle);

        $result_msg = $responseDecoded['data']['translations'][0]['translatedText'];

        if (!isset($result_msg))
            throw new IllegalApiParamsException('Translation failed');

        $response = APIConstants::MESSAGE;
        $jsonObj->$response = $result_msg . ' ' . $name;
    }


    function getBase64($jsonObj, $params)
    {
        if (!isset($params) || !isset($params['file'])) {
            throw new IllegalApiParamsException("Invalid parameters");
        }
        $file_array = $params['file'];

        $file_content = file_get_contents($file_array['tmp_name']);

        $response = APIConstants::BASE64;
        $jsonObj->$response = base64_encode($file_content);
    }


}
