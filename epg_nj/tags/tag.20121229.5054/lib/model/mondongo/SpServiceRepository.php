<?php

/**
 * Repository of SpService document.
 */
class SpServiceRepository extends \BaseSpServiceRepository
{
    /**
     * 按tag获取service
     * @param <string> $tag
     * @return <array>
     * @author lifucang (2012-11-7)
     */
    public function getServicesByTag($tag=null,$sort='logicNumber',$order=1) 
    {
        if(empty($tag)){
	        return $this->find(
	                    array(
	                        "sort" => array($sort => $order),
	                    )
	                );        	
        }else{
            if(is_array($tag)){
                $tag_query=array('$in'=>$tag);
            }else{
                $tag_query=$tag;
            }
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'tags' => $tag_query,
	                        ),
	                        "sort" => array($sort => $order),
	                    )
	                );
        }
    }
    /**
     * 按code查找spservice
     * @param <string> $tag
     * @return <array>
     * @author lifucang (2012-11-7)
     */
    public function getServicesByChannelCode($code) 
    {
        return $this->findOne(
                    array(
                        'query' => array(
                            'channel_code' => $code,
                        )
                    )
                );

    }
    /**
     * 按code查找spservice
     * @param <string> $tag
     * @return <array>
     * @author lifucang (2012-11-7)
     */
    public function getSpByname($name) 
    {
        return $this->findOne(
                    array(
                        'query' => array(
                            'name' => $name,
                        )
                    )
                );
    }
    /**
     * 按code查找spservice
     * @param <string> $tag
     * @return <array>
     * @author lifucang (2012-11-7)
     */
    public function getCodeByname($name) 
    {
        $sp=$this->findOne(
                    array(
                        'query' => array(
                            'name' => $name,
                        )
                    )
                );
         if($sp)       
             return $sp->getChannelCode();
         else
             return null;    
    }
}