<?php
/**
 * RockMongo configuration
 *
 * Defining default options and server configuration
 * @package rockmongo
 */
 
$MONGO = array();
$MONGO["features"]["log_query"] = "off";//log queries
$MONGO["features"]["theme"] = "default";//theme
$MONGO["features"]["plugins"] = "on";//plugins

$i = 0;

/**
 * mini configuration for another mongo server
 */

$MONGO["servers"][$i]["mongo_name"] = "172.31.201.81";
$MONGO["servers"][$i]["mongo_host"] = "172.31.201.81";
$MONGO["servers"][$i]["mongo_port"] = "27017";

$MONGO["servers"][$i]["mongo_user"] = "sa";//mongo authentication user name, works only if mongo_auth=false
$MONGO["servers"][$i]["mongo_pass"] = "sa";//mongo authentication password, works only if mongo_auth=false
$MONGO["servers"][$i]["mongo_auth"] = false;//enable mongo authentication?

$MONGO["servers"][$i]["control_auth"] = true;//enable control users, works only if mongo_auth=false
$MONGO["servers"][$i]["control_users"]["admin"] = "admin";
$i ++;



$MONGO["servers"][$i]["mongo_name"] = "10.20.20.239";
$MONGO["servers"][$i]["mongo_host"] = "10.20.20.239";
$MONGO["servers"][$i]["mongo_port"] = "27017";

$MONGO["servers"][$i]["mongo_user"] = "sa";//mongo authentication user name, works only if mongo_auth=false
$MONGO["servers"][$i]["mongo_pass"] = "sa";//mongo authentication password, works only if mongo_auth=false
$MONGO["servers"][$i]["mongo_auth"] = false;//enable mongo authentication?

$MONGO["servers"][$i]["control_auth"] = true;//enable control users, works only if mongo_auth=false
$MONGO["servers"][$i]["control_users"]["admin"] = "admin";
$i ++;
?>