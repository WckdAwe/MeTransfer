<?php
namespace codebase\Databases;

class PHPDataObjects {
    private static $instance;
    public function __connect(){
        if (self::$instance){
            exit("Instance on Connection already exists.") ;
        }

        try {
            $dbh = new \PDO('mysql:host='.__DBCONF['host'].';dbname='.__DBCONF['dbname'],
                            __DBCONF['username'], __DBCONF['password']);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $dbh;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return null;
    }

    public static function closeConnection(){
        self::$instance = null;
    }
    
    public static function getInstance(){
        if (!self::$instance){
            $connector = new self();
            self::$instance = $connector->__connect();
        }
        return self::$instance ;
    }
}

