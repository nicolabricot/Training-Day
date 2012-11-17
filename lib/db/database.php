<?php
namespace lib\db;
use Exception;
use PDO;
use SimpleXMLElement;
/**
 * @author Karl
 */
class DataBase {
    private static $instance;
    private static $count = 0;

    static public function getInstance(){
        if(!isset(self::$instance)){
            $cfgFile = 'config/database.xml';
            if(!file_exists($cfgFile)){
                $xmlOut = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<root>\n\t<dsn>mysql:host=localhost;dbname=taches</dsn>\n\t<login>root</login>\n\t<password></password>\n</root>";
                $xmlFile = fopen($cfgFile, 'w');
                fwrite($xmlFile,$xmlOut);
                fclose($xmlFile);
            }
            $xmlConfig = new SimpleXMLElement($cfgFile, null, true);
        
            self::$instance = new PDO($xmlConfig->dsn, $xmlConfig->login, $xmlConfig->password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        self::$count++;
        return self::$instance;
    }
    static function install($filename){
        if(is_file($filename)){
            $requests = preg_split('/;/', file_get_contents($filename), -1, PREG_SPLIT_NO_EMPTY);
            foreach($requests as $request) {  
                self::getInstance()->query($request);
            }
        }
        else{
            throw new Exception('SQL installation file not found.');
        }
    }
    static function countQuery(){
        return self::$count;
    }
    static public function lastInsertId(){
        return self::getInstance()->lastInsertId();
    }
}

?>
