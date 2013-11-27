<?php

/**
 * Repository of ReportProgramnum document.
 */
class ReportProgramnumRepository extends \BaseReportProgramnumRepository
{
    /*
     * 验证运营商指定日期上报节目的次数
     * @param object $report_sp
     * @param string $date
     * @return object $report_programnum
     * @author wangnan
     */
    public function device_report_programnum($reportSp,$date){
        $query = array( 'spid'=>(string)$reportSp->getId(),
        				'date'=>$date
        );
        $report_programnum = $this->findOne(array('query'=>$query));
        if(!$report_programnum){
            $report_programnum = new reportprogramnum();
            $report_programnum->setSpid((string)$reportSp->getId());
            $report_programnum->setNewworkId($reportSp->getNewworkId());
            $report_programnum->setNewworkName($reportSp->getNewworkName());
            $report_programnum->setVersion($reportSp->getVersion());
            $report_programnum->setCity($reportSp->getCity());
            $report_programnum->setDate($date);
            $report_programnum->setNum(1);//第一次上报
            $report_programnum->Save();
        }
        else 
        {
            $num = $report_programnum->getNum();
            $num = $num + 1;
            $report_programnum->setNum($num);
            $report_programnum->Save();        	
        }
        return $report_programnum;

    }		
}