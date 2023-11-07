<?php
// require('api.php');

// // require('../database/dbConnect.php');

// class studentAccount extends API {
    
//     private $response;
//     private $studentName;
//     private $studentEmail;
//     private $studentpassword;

//     // public function __construct()
//     // {
//     //     // parent::__construct(); // Call the constructor of the parent class
//     //     $this->db = new Database;
//     //     $this->studentName = $_REQUEST['studentName'];
//     //     $this->studentEmail = $_REQUEST['studentEmail'];
//     //     $this->studentPassword = $_REQUEST['studentPassword'];
//     // }

//     public function createAccount(){
//         $this->response = array('status'=>true);
//         $this->sendResponse($this->response);
        
        
//     } 
// }

// $student = new studentAccount;
// $student->createAccount();

//VIDEP10
require('api.php');
require('../database/dbConnect.php');

class studentAccount extends API
{
    private $db;


    private $requestPayload;
    private $responsePayload;

    private $allowedMethods = array(
        'POST'
    );

    private $requiredFields = array(
        'studentName',
        'studentEmail',
        'studentPassword'
    );

    private $allFields = array(
        'studentName',
        'studentEmail',
        'studentPassword'
    );

    public function __construct()
    {
        $this->db = new Database;
        parent::__construct($this->allowedMethods);
        
        $this->requestPayload = $_REQUEST;
        $this->validateFields();

    }

    public function createAccount()
    {
        $this->sendResponse($this->requestPayload);
    }

    private function validateFields()
    {
        if ($this->is_present_in($this->requiredFields, $this->requestPayload)) {
            $this->responsePayload = array('status'=> false, 'entity' => 'error', 'message' => 'Required parameters are not present in the payload.'); 
            $this->sendResponse($this->responsePayload);
        } else {
            foreach ($this->requiredFields as $requiredField) {
                if (empty($this->requestPayload [$requiredField])) {
                    $this->responsePayload= array('status' => false, 'entity' => 'error', 'message' => 'Required parameters are not present in the payload.');
                    $this->sendResponse($this->responsePayload);
                }
            }
        }
        $temp = $this->requestPayload;
        unset($this->requestPayload);
        foreach ($temp as $key => $value){
            if (array_key_exists($key, array_flip($this->allFields))) {
                $this->requestPayload [$key] = $value;
            }
        }
        unset($temp);
    }
    private function is_present_in(array $keys, array $keyChain)
    {
        
    }
}



$student = new studentAccount;
$student->createAccount();