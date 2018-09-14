<?php

namespace App;

class SmartXmx
{
    public static $username = "";
    public static $password = "";
    public $sender = "";
    public $recipient = [];
    public $message = "";
    public static $response = [
        'OK' => 'Successful',
        '2904' => 'SMS Sending Failed',
        '2905' => 'Invalid username/password combination',
        '2906' => 'Credit exhausted',
        '2907' => 'Gateway unavailable',
        '2908' => 'Invalid schedule date format',
        '2909' => 'Unable to schedule',
        '2910' => 'Username is empty',
        '2911' => 'Password is empty',
        '2912' => 'Recipient is empty',
        '2913' => 'Message is empty',
        '2914' => 'Sender is empty',
        '2915' => 'One or more required fields are empty',
        '2916' => 'Sender ID not allowed'
    ];

    /**
     * SmartXmx
     *
     * @param mixed $username
     * @param mixed $password
     * @return void
     */
    public function __construct($username, $password) {
        self::$username = $username;
        self::$password = $password;
    }

    // http://api.smartsmssolutions.com/smsapi.php?username=YourUsername&password=YourPassword&sender=SenderID&recipient=234809xxxxxxx,2348030000000&message=YourMessage
    /**
     * send
     *
     * @param mixed $sender
     * @param mixed $recipent
     * @param mixed $message
     * @return void
     */
    public function send($sender, $recipent, $message) {
        // $json = json_decode(file_get_contents('http://api.smartsmssolutions.com/smsapi.php?username='.$this->username.'&password='.$this->password.'&balance=true'), true);

        return false;
    }

    // http://api.smartsmssolutions.com/smsapi.php?username=YourUsername&password=YourPassword&balance=true&
    /**
     * checkSmsBalance
     *
     * @return void
     */
    public static function checkSmsBalance() {
        return json_decode(file_get_contents('http://api.smartsmssolutions.com/smsapi.php?username='.self::$username.'&password='.self::$password.'&balance=true'));
    }

    /**
     * interpreteResponse
     *
     * @param mixed $response
     * @return void
     */
    public static function interpreteResponse($response) {
        foreach (self::$response as $key => $value) {
            if ($response == $key) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set the value of sender
     *
     * @return  self
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get the value of recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set the value of recipient
     *
     * @return  self
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}