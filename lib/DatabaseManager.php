<?php
require_once("../apiClasses/APIError.php");
define("DatabaseConnectorShouldIgnoreError",false);

class DatabaseConnector{

    public static $hostName='',$user='sonic_s1',$password='741285',$database='sonic_1';

    private static $isConnected = false;

    private static function checkError($sql){
        if(mysql_errno() > 0){
            throwError(4000,"MysqlError: " . mysql_errno() . " \n description: " . mysql_error() . " sql: " . $sql);
        }
//            die("$sql : <br/>" . mysql_error());

    }


    public static function get_results( $sql,$ignoreError = DatabaseConnectorShouldIgnoreError ){
        if(DatabaseConnector::$isConnected == false){
            DatabaseConnector::connect();
            DatabaseConnector::$isConnected = true;
        }
        $resultset = mysql_query($sql);
        if(!$ignoreError)
            DatabaseConnector::checkError($sql);
        $results = array();
        while($row = mysql_fetch_object($resultset))
            $results[] = $row;
        return $results;
    }

    public static function get_single( $sql,$ignoreError = DatabaseConnectorShouldIgnoreError ){
        if(DatabaseConnector::$isConnected == false){
            DatabaseConnector::connect();
            DatabaseConnector::$isConnected = true;
        }
        $sql .= ' LIMIT 1';
        $resultset = mysql_query($sql);
        if(!$ignoreError)
            DatabaseConnector::checkError($sql);
        return mysql_fetch_object($resultset);
    }

    public static function get_value( $sql,$ignoreError = DatabaseConnectorShouldIgnoreError ){
        if(DatabaseConnector::$isConnected == false){
            DatabaseConnector::connect();
            DatabaseConnector::$isConnected = true;
        }
        $sql .= ' LIMIT 1';
        $resultset = mysql_query($sql);
        $row = mysql_fetch_array($resultset, MYSQL_NUM);
        if(!$ignoreError)
            DatabaseConnector::checkError($sql);
        return $row[0];
    }

    public static function query( $sql,$ignoreError = DatabaseConnectorShouldIgnoreError ){
        if(DatabaseConnector::$isConnected == false){
            DatabaseConnector::connect();
            DatabaseConnector::$isConnected = true;
        }
        mysql_query($sql);
        if(!$ignoreError)
            DatabaseConnector::checkError($sql);
    }

    public static function connect(){
        mysql_connect(DatabaseConnector::$hostName,DatabaseConnector::$user,DatabaseConnector::$password);
        mysql_select_db(DatabaseConnector::$database);
    }

}