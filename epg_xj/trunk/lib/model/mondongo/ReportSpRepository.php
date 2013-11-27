<?php

/**
 * Repository of ReportSp document.
 */
class ReportSpRepository extends \BaseReportSpRepository
{
    /*
     * 验证运营商
     * @param string $device_id
     * @param string $newwork_name
     * @param string $version
     * @param string $city
     * @return object $report_sp
     * @author wangnan
     */
    public function device_report($newwork_id,$newwork_name,$version,$city){
        $query = array( 'newwork_id'=>$newwork_id,
        				'newwork_name'=>$newwork_name,
        				'version'=>$version,
        				'city'=>$city,
        );
        $report_sp = $this->findOne(array('query'=>$query));
        if(!$report_sp){
            $report_sp = new reportsp();
            $report_sp->setNewworkId($newwork_id);
            $report_sp->setNewworkName($newwork_name);
            $report_sp->setVersion($version);
            $report_sp->setCity($city);
            $report_sp->setNum(1);//第一次上报
            $report_sp->Save();
        }
        else 
        {
            $num = $report_sp->getNum();
            $num = $num + 1;
            $report_sp->setNum($num);
            $report_sp->Save();        	
        }
        return $report_sp;

    }	
    /*
     * 通过运营商id、运营商城市 来返回sp
     * @param string $newwork_id
     * @return string $city
     * @author wangnan
     */
    public function getOneSpByNIC($newwork_id,$city){
        $query = array('newwork_id'=>$newwork_id,'city'=>$city);
        return $this->findOne(array('query'=>$query));
    }    
}