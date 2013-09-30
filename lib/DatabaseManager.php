<?php
/**
 *   Sonicraph
 *   20.09.2013
 *   Yunus Eren Güzel
 *   Database Manager Class
 */

class DatabaseConnector{

    public static $hostName='',$user='root',$password='',$database='sonicraph';

    private static $isConnected = false;

    private static function checkError($sql){
        if(mysql_errno() > 0)
            die("$sql : <br/>" . mysql_error());

    }

    public static function get_results( $sql,$ignoreError = true ){
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

    public static function get_single( $sql,$ignoreError = true ){
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

    public static function get_value( $sql,$ignoreError = true ){
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

    public static function query( $sql,$ignoreError = true ){
        if(DatabaseConnector::$isConnected == false){
            DatabaseConnector::connect();
            DatabaseConnector::$isConnected = true;
        }
        mysql_query($sql);
        if(!$ignoreError)
            DatabaseConnector::checkError($sql);
    }

    private static  function connect(){
        mysql_connect(DatabaseConnector::$hostName,DatabaseConnector::$user,DatabaseConnector::$password);
        mysql_select_db(DatabaseConnector::$database);
    }

}