<?php
try{
$mongo = new Mongo("mongodb://sa:sa@110.173.3.219,110.173.3.229,110.173.3.228:27017",array('replicaSet' => 'rs1',"connect" => 'replicaSet'));
    $mongo->setReadPreference(Mongo::RP_SECONDARY_PREFERRED);
}catch(Exception $e){
    echo "error ====> ".$e->getMessage()."<br>";
}

try{
    
    $epg_db = $mongo->selectDB("epg");
    $sps_dc = $epg_db->selectCollection("sp_service");
    
    $jilus = $sps_dc->find()->limit(5);
    while($jilus->hasNext()){
        $rs = $jilus->getNext();
        echo $rs["name"]."<br>";
    }
    echo "<pre>Slave:";
    print_r($mongo->getSlave());
    // echo "<p>";
    // print_r($mongo->getSlaveOkay());
    //echo "<p>PoolSize:<br>";
    //print_r($mongo->getPoolSize());
    echo "<p>Hosts:<br>";
    print_r($mongo->getHosts());
    print_r($jilus->info());
    echo "</pre>";
    

}catch(MongoCursorException $e){
    echo "error ====> ".$e->getMessage()."<br>";
}

$mongo->close();
