<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author User
 */
class User {
    private $login = "";
    private $loggedIn = FALSE;
        
    public function __construct($login=NULL, $password=NULL)
    {
        if(!isset($_SESSION))
        {
            $lifetime = 180;
            session_set_cookie_params($lifetime, "/");
            session_start();
        }
        if(isset($login) && isset($password))
        {
            $this->logIn($login, $password);
        }
        else if(isset($_SESSION['login']) && isset($_SESSION['loggedIn']))
        {
            $this->login = $_SESSION['login'];
            $this->loggedIn = $_SESSION['loggedIn'];
        }
    }

    public function logIn($login, $password)
    {
        if($this->read($login) == md5($password))
        {
            $this->login = $login;
            $this->loggedIn = TRUE;
            $_SESSION['login'] = $login;
            $_SESSION['loggedIn'] = TRUE;
        }
    }
    
    public function logOut()
    {
        $this->login = "";
        $this->loggedIn = FALSE;
        session_unset();
    }
    
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }
    
    public function getLogin()
    {
        return $this->login;
    }

    private function read($login)
    {
        $file = fopen("./password.txt", "r");
        while (!feof($file))
        {
            $line = fgetcsv($file);
            if(count($line) == 2)
            {
                if($line[0] == $login)
                {
                    return $line[1];
                }
            }
        }
        fclose($file);
    }
}