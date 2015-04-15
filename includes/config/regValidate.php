<?php

class regValidate {

    //fields
    public $username;
    public $email;
    public $password;
    public $retypePassword;
    private $errMsg;

    //constructor
    public function __construct($u, $e, $p, $rp) {
        $this->username = $u;
        $this->email = $e;
        $this->password = $p;
        $this->retypePassword = $rp;
        $this->errMsg = array();
    }

    public function validate() {
        /* Access the regex standards variables defined in config.php */
        require 'config.php';

        $status = FALSE;
        global $errMsg;
        $counter = 0;
        if ((empty($this->username)) || (strlen($this->username) < 6) || (preg_match('/'.$regexUsername.'/', $this->username) === 0)) {
            array_push($this->errMsg, "<b>Please enter a valid username.</b><br>");
            $counter++;
        }
        if ((empty($this->email)) || (strlen($this->email) < 6) || (preg_match('/'.$regexEmail.'/', $this->email) === 0)) {
            array_push($this->errMsg, "<b>Please enter a valid email adress.</b><br>");
            $counter++;
        }
        if ((empty($this->password)) || (strlen($this->password) < 6) || (preg_match($regexPassword, $this->password) === 0)) {
            array_push($this->errMsg, "<b>Password must be min. 6 characters long and should consist of min 1 lowercase letter, 1 uppercase letter and 1 number.</b><br>");
            $counter++;
        }
        if (!($this->password === $this->retypePassword) || ($this->retypePassword==="") || ($this->retypePassword===NULL)) {
            array_push($this->errMsg, "<b>The password and the retyped password DO NOT match.</b><br>");
            $counter++;
        }
        if ($counter == 0) {
            $status = TRUE;
        }
        return $status;
    }

    public function printErrMsg() {
        echo "<ul>";
        foreach ($this->errMsg as $value) {
            echo "<li>$value</li>";
        }
        echo "</ul>";
    }

}

?>