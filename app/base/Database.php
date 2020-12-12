<?php
    class Database{
        private static  $db;
        private function __construct(){

        }
        public static function open(){
            if(self::$db == null){
                self::$db = new mysqli('localhost','root','','database');
                self::$db->set_charset('UTF8');
            }
            return self::$db;
        }

    }
?>