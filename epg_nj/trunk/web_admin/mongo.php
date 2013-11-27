<?php
try{
    //$mongo = new Mongo("mongodb://sa:sa@172.31.201.84");
    //$mongo = new MongoClient("mongodb://sa:sa@172.31.201.81,172.31.201.84,172.31.201.82,172.31.201.83/epg?readPreference=secondarypreferred&timeout=100&replicaSet=rs1");
    //$mongo = new Mongo("mongodb://sa:sa@172.31.201.81,172.31.201.84,172.31.201.82,172.31.201.83/epg?readPreference=secondarypreferred&connectTimeoutMS=100&socketTimeoutMS=100&wtimeout=100&replicaSet=rs1");
    $mongo = new Mongo("mongodb://sa:sa@172.31.201.81,172.31.201.84,172.31.201.82,172.31.201.83/epg?readPreference=secondarypreferred&connectTimeoutMS=1000&socketTimeoutMS=1000&replicaSet=rs1");
}catch(Exception $e){
    echo "error ====> ".$e->getMessage()."<br>";
}


try{
    
    $epg_db = $mongo->selectDB("epg");
    $sps_dc = $epg_db->selectCollection("sp_service");
    //exit;
    //$user_dc->insert(array("username" => "tttt".date("His")));
    //var_dump($user_dc->info());
    
    $jilus = $sps_dc->find()->limit(5);
    while($jilus->hasNext()){
        $rs = $jilus->getNext();
        echo $rs["name"]."<br>";
    }
    echo "<pre><font color='#ff0000'>Slave:</font><br>";
    print_r($mongo->getSlave());
    echo "<pre><font color='#ff0000'>getReadPreference:</font><br>";
    print_r($mongo->getReadPreference());
    echo "<pre><font color='#ff0000'>Timeout:</font><br>";
    print_r($mongo->Timeout);
    echo "<pre><font color='#ff0000'>getConnectTimeoutMS:</font><br>";
    print_r($mongo->ConnectTimeout);
    echo "<p><font color='#ff0000'>getSlaveOkay</font><br>";
    print_r($mongo->getSlaveOkay());
    echo "<p><font color='#ff0000'>PoolSize:</font><br>";
    print_r($mongo->getPoolSize());
    echo "<p><font color='#ff0000'>Hosts:</font><br>";
    print_r($mongo->getHosts());
    //print_r($jilus->info());
    echo "</pre>";
    

}catch(MongoCursorException $e){
    echo "error ====> ".$e->getMessage()."<br>";
}

$mongo->close();
