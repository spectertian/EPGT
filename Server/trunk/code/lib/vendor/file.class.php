<?php
/**
 * 文件操作类
 * 
 */
class FSC{
    // 函数名: isFile
    // 功能: 判断指定目录下是否存在指定文件
    // 参数: $folder 目录  $file 文件
    function isFile($folder,$file){
        $files=scandir($folder);
        if(in_array($file,$files))
            return true;
        else
            return false;    
    }
    // 函数名: getfilesource
    // 功能: 得到指定文件的内容
    // 参数: $file 目标文件
    // test passed
    function getfilesource($file){
        if($fp=fopen($file,'r')){
            $filesource=fread($fp,filesize($file));
            fclose($fp);
            return $filesource;
        }
        else
            return false;
    }
    
    // 函数名: writefile
    // 功能: 创建新文件，并写入内容，如果指定文件名已存在，那将直接覆盖
    // 参数: $file -- 新文件名
    // $source  文件内容
    //test passed
    function writefile($file,$source){
        if($fp=fopen($file,'w')){
            $filesource=fwrite($fp,$source);
            fclose($fp);
            return $filesource;
        }
        else
            return false;
    }
    
    // 函数名: movefile
    // 功能: 移动文件
    // 参数: $file -- 待移动的文件名
    // $destfile -- 目标文件名
    // $overwrite 如果目标文件存在，是否覆盖.默认是覆盖.
    // $bak 是否保留原文件 默认是不保留即删除原文件
    // test passed
    function movefile($file,$destfile,$overwrite=1,$bak=0){
        if(file_exists($destfile)){
            if($overwrite)
                unlink($destfile);
            else
                return false;
        }
        if($cf=copy($file,$destfile)){
            if(!$bak)
                return(unlink($file));
            }
        return($cf);
    }
      
    // 函数名: movedir
    // 功能: 这是下一涵数move的附助函数，功能就是移动目录
    
    function movedir($dir,$destdir,$overwrite=1,$bak=0){
         @set_time_limit(600);
        if(!file_exists($destdir))
            FSC::notfate_any_mkdir($destdir);
        if(file_exists($dir)&&(is_dir($dir)))
            {
            if(substr($dir,-1)!='/')$dir.='/';
            if(file_exists($destdir)&&(is_dir($destdir))){
            if(substr($destdir,-1)!='/')$destdir.='/';
                $h=opendir($dir);
                while($file=readdir($h)){
                    if($file=='.'||$file=='..')
                        {
                        continue;
                        $file="";
                    }
                    if(is_dir($dir.$file)){
                        if(!file_exists($destdir.$file))
                            FSC::notfate_mkdir($destdir.$file);
                        else
                            chmod($destdir.$file,0777);
                        FSC::movedir($dir.$file,$destdir.$file,$overwrite,$bak);
                        FSC::delforder($dir.$file);
                        }
                    else
                    {
                        if(file_exists($destdir.$file)){
                            if($overwrite)unlink($destdir.$file);
                            else{
                                continue;
                                $file="";
                                }
                        }
                        if(copy($dir.$file,$destdir.$file))
                            if(!$bak)
                                if(file_exists($dir.$file)&&is_file($dir.$file))
                                    @unlink($dir.$file);
                    }
                }
            }
            else
                return false;
        }
        else
            return false;
    }
    
    // 函数名: move
    // 功能: 移动文件或目录
    // 参数: $file -- 源文件/目录
    //       $path -- 目标路径
    //       $overwrite -- 如是目标路径中已存在该文件时，是否覆盖移动
    //                  --  默认值是1, 即覆盖
    //       $bak  -- 是否保留备份(原文件/目录)
    
    function move($file,$path,$overwrite=1,$bak=0)
         {
        if(file_exists($file)){
            if(is_dir($file)){
                if(substr($file,-1)=='/')$dirname=basename(substr($file,0,strlen($file)-1));
                else $dirname=basename($file);
                if(substr($path,-1)!='/')$path.='/';
                if($file!='.'||$file!='..'||$file!='../'||$file!='./')$path.=$dirname;
                FSC::movedir($file,$path,$overwrite,$bak);
                if(!$bak)FSC::delforder($file);
                }
            else{
                if(file_exists($path)){
                    if(is_dir($path))chmod($path,0777);
                    else {
                        if($overwrite)
                            @unlink($path);
                        else
                            return false;
                    }
                }
                else
                    FSC::notfate_any_mkdir($path);
                if(substr($path,-1)!='/')$path.='/';
                FSC::movefile($file,$path.basename($file),$overwrite,$bak);
            }
        }
        else
            return false;
    }
    
    // 函数名: delfolder
    // 功能: 删除目录,不管该目录下是否有文件或子目录，全部删除哦，小心别删错了哦!
    // 参数: $file -- 源文件/目录
    function delfolder($file) {
         chmod($file,0777);
         if (is_dir($file)) {
               $handle = opendir($file);
               while($filename = readdir($handle)) {
                   if ($filename != "." && $filename != "..")
                    {
                        FSC::delfolder($file."/".$filename);
                    }
              }
              closedir($handle);
              return(rmdir($file));
         }else {
            unlink($file);
         }
    }
    // 函数名: delfolderOne
    // 功能: 删除目录下的文件!
    // 参数: $file -- 源目录
    function delfolderOne($file) {
        chmod($file,0777);
        if (is_dir($file)) {
            $handle = opendir($file);
            while($filename = readdir($handle)) {
               if ($filename != "." && $filename != ".."){
                    unlink($file."/".$filename);
                }
            }
            closedir($handle);
            return true;
        }else {
            unlink($file);
            return true;
        }
    }    
    // 函数名: notfate_mkdir
    // 功能: 创建新目录,这是来自php.net的一段代码.弥补mkdir的不足.
    // 参数: $dir -- 目录名
    
    
    function notfate_mkdir($dir,$mode=0777){
        if(is_dir($dir)) return false;
        $u=umask(0);
        $r=mkdir($dir,$mode);
        umask($u);
        return $r;
    }
    
    // 函数名: notfate_any_mkdir
    // 功能: 创建新目录,与上面的notfate_mkdir有点不同，因为它多了一个any,即可以创建多级目录
    //         如:notfate_any_mkdir("abc/abc/abc/abc/abc")
    // 参数: $dirs -- 目录名
    
    
    function notfate_any_mkdir($dirs,$mode=0777)
    {
      if(!strrpos($dirs,'/'))
        {
          return(FSC::notfate_mkdir($dirs,$mode));
      }else
          {
          $forder=explode('/',$dirs);
          $f='';
          for($n=0;$n<count($forder);$n++)
              {
              if($forder[$n]=='') continue;
              $f.=((($n==0)&&($forder[$n]<>''))?(''):('/')).$forder[$n];
              if(file_exists($f)){
                  chmod($f,0777);
                  continue;
                  }
              else
                  {
                  if(FSC::notfate_mkdir($f,$mode)) continue;
                  else
                      return false;
              }
            }
            return true;
          }
    }




}
