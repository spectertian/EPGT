<?php
set_time_limit(0); 

echo str_repeat("　",4096); //ie下 需要先发送256个字节
echo "<br/>"; 

ob_end_flush();
for($i=0;$i<10;$i++){ 
	echo "Now Index is :". $i." "."<br/>"; 
	flush(); 
	sleep(1); 
}
?>