<?php
namespace MamunApp\PHP\Routing;

function ddd($data){
    // echo "<pre>";
    echo "<br>---------------<br>";
    if(is_iterable($data)){
        print_r($data);
    }else{
        echo $data;
    }
    echo "<br>---------------<br>";
    // echo "</pre>";
}

class Router{

    // public static $url = ''; // /{product}/kanon/{id}/?page=3';
    private static $nomatch = true;
    private static $group = null;

    public function __construct()
    {
        
    }
    
    public static function requestMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public static function requestURL(){
        return $_SERVER['REQUEST_URI'];
    }    

    public function pathToPattern($url = '')
    {
        /**
         * @param INPUT $url Example: /prodcut/{product}/{id} 
         * @param OUTPUT $paramType Example: /prodcut/(\w+)/(\d+)
         */

        if(($url) == '/'){ $url = "/*"; }
        $url = rtrim(explode('?', $url)[0], '/?');
      
        $isCurly = strpos($url, '{');
        $isClone = strpos($url, ':');

        if ($isCurly != null) {
            $url = preg_replace("~{([^{}]*)}~", "(\w+-?-?-?-?-?-?-?\w+)", $url); //  (\w+-?-?-?-?-?-?-?\w+)  
        }
        if ($isClone != null) {
            $url = preg_replace("~(:\w+)~", "(\w+-?-?-?-?-?-?-?\w+)", $url); //  (\w+-?-?-?-?-?-?-?\w+)  
        }
        $url = str_replace('//', '/', $url);
        return $url;
    }

    public static function process($url,  $callback, $options=[])
    {
        $group = self::$group;
        $pattern = self::pathToPattern($group.$url);
        $pattern = "~^{$pattern}$~";        
        $params = self::getMatched($pattern);
        if ($params) {
            self::$nomatch = false;           
            if (is_callable($callback)) {    
                if(count($params)){
                    $args = array_slice($params, 1);
                    $callback(...$args);
                }else{
                    $callback(...$params);  
                }
                return;
               
            }
            return false;
        }
    }

    public static function getMatched($pattern)
    {
        $url = rtrim(explode('?', self::requestURL())[0], '/');
        if (preg_match($pattern, $url, $matches)) {
            return $matches;
        }
        return false;
    }

    public static function group($prefix, $callback){
        if(strlen($prefix)){
            if(!self::isUniqeGroup($prefix)){
                echo sprintf('Sorry, {%s} is already used in top as a router group. Use another unique name', $prefix);
                die();
            }
            $prefix = ltrim($prefix, '/');
            $prefix = rtrim($prefix, '/');
            self::$group = '/'.$prefix;

            $selfIntance = new self();
            
            if(is_callable($callback)){
                $callback($selfIntance);
            }
        }
    }

    private static function isUniqeGroup($groupName){
        if(!empty(session_id())) session_start();
        if(!isset($_SESSION['ROUTE_GROUP'])) $_SESSION['ROUTE_GROUP'] = null;
        if($_SESSION['ROUTE_GROUP'] == $groupName){
            return False;
        }else{
            $_SESSION['ROUTE_GROUP'] = $groupName;
            return True;
        }
    }

   
    public static function get($url,  $callback='', $options=[]){ 

        if(self::requestMethod() != 'GET') {
            return;
        };
        self::process($url,  $callback, $options);
    }
    
    public static function post($url,  $callback='', $options=[]){

        if(self::requestMethod() != 'POST') {
            return;
        };
        self::process($url,  $callback, $options);
    }

    public static function delete($url,  $callback='', $options=[]){

        if(self::requestMethod() != 'DELETE') {
            return;
        };
        self::process($url,  $callback, $options);
    }

    public static function options($url,  $callback='', $options=[]){

        if(self::requestMethod() != 'OPTIONS') {
            return;
        };
        self::process($url,  $callback, $options);
    }

    public static function patch($url,  $callback='', $options=[]){

        if(self::requestMethod() != 'PATCH') {
            return;
        };
        self::process($url,  $callback, $options);
    }

    
        
    

    public static function cleanup()
    {
        if(self::$nomatch){
            echo sprintf("404 page not found ------ METHOD::%s", self::requestMethod());
            die();
        }
    }

    


}