<?php

class apiBaseClass {

    //create default JSON string
    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }
}

?>