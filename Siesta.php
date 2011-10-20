<?php

namespace Siesta;

class Siesta {

    ///////////////////////////////
    //define protected properties//
    ///////////////////////////////
    protected $_handler;
    protected $_methods;

    //////////////////////
    //define constructor//
    //////////////////////
    public function __construct($handler,$methods,$urlBase = "/") {
        $this->_handler = $handler;
        $this->_methods = $methods;
        $this->_urlBase = $urlBase;
    }

    /////////////////////////
    //define public methods//
    /////////////////////////
    public function handleRawRequest() {
        $url = $this->getFullUrl($_SERVER);
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
            case 'HEAD':
                $arguments = $_GET;
                break;
            case 'POST':
                $arguments = $_POST;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $arguments);
                break;
        }
        
        $accept = $_SERVER['HTTP_ACCEPT'];
        $this->handleRequest($url, $method, $arguments, $accept);
    }

    public function handleRequest($url, $method, $arguments, $accept) {
        
        //check method is allowed    
        if(!$this->_isMethodAllowed($method))
            return false;
        
        //get action to perform
        $urlParts = parse_url($url);
        print_r($urlParts);
        return false;
    }

    ////////////////////////////
    //define protected methods//
    ////////////////////////////
    protected function _setStatusCode($code,$message){
        header($message,true,$code);
    }
    
    protected function _error($error){
           
    }
    
    protected function _getFullUrl($_SERVER) {
        $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $location = $_SERVER['REQUEST_URI'];
        if ($_SERVER['QUERY_STRING'])
            $location = substr($location, 0, strrpos($location, $_SERVER['QUERY_STRING']) - 1);
    
        return $protocol.'://'.$_SERVER['HTTP_HOST'].$location;
    }
    
    protected function _isMethodAllowed($method) {
        if(in_array($method,$methods)){
            return true;
        } else {
            $this->_setStatusCode(405,'Allow: ' . $this->supportedMethods);
            return false;
        }
    }
}