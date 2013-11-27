<?php
/**
 * 图像处理
 */
class ImageService {
    
    static public function check_image($image_file) {
        if ($data = GetImageSize($image_file)) {
            if($data[2] <= 3 ){
                return $data;
            }else{
                return false;
            }
        }
        return false;
    }
    
    /**
     * 生成缩略图
     * @param 源图片路径 $srcFile
     * @param 缩略图路径 $dstFile
     * @param 缩略图宽度 $dstW
     * @param 缩略图高度 $dstH
     */
    static public function create_thumb($srcFile, $dstFile, $dstW, $dstH, $fill = false)
    {
        $data = GetImageSize($srcFile);
        switch ($data[2]) {
            case 1:
                $srcImg = @ImageCreateFromGIF($srcFile);
                break;
            case 2:
                $srcImg = @ImageCreateFromJPEG($srcFile);
                break;
            case 3:
                $srcImg = @ImageCreateFromPNG($srcFile);
                break;
            default:
                return;
                break;
        }
        if ($srcImg) {
            $srcW = ImageSX($srcImg);
            $srcH = ImageSY($srcImg);
            $dstX = 0;
            $dstY = 0;
        
            if ($srcW * $dstH > $srcH * $dstW) {// srcW/srcH > dstW/dstH ，源比目标扁
                $dstRealW = max($dstW, 1);
                $dstRealH = max(round($srcH * $dstRealW / $srcW), 1);
            } else {
                $dstRealH = max($dstH, 1);
                $dstRealW = max(round($srcW * $dstRealH / $srcH), 1);
            }
        
            if ($fill) {
                $dstX = floor(($dstW - $dstRealW) / 2);
                $dstY = floor(($dstH - $dstRealH) / 2);
                
                if ($data[2] == 3) {
                    $dstImg = imageCreate($dstRealW, $dstRealH);
                } else {
                    $dstImg = ImageCreateTrueColor($dstRealW, $dstRealH);
                }
                
                $backColor = ImageColorAllocate($dstImg, 255, 255, 255);//缩图空出部分的背景色
                ImageFilledRectangle($dstImg, 0, 0, $dstW, $dstH, $backColor);
            } else {
                if ($data[2] == 3) { // 如果是 PNG 类型图片 则不创建真彩色图片
                    $dstImg = imageCreate($dstRealW, $dstRealH);
                } else {
                    $dstImg = ImageCreateTrueColor($dstRealW, $dstRealH);
                }   
            }
            
            //ImageCopyResized($dstImg, $srcImg, 0, 0, 0, 0, $dstRealW, $dstRealH, $srcW, $srcH);
            imagecopyresampled($dstImg, $srcImg, $dstX, $dstY, 0, 0, $dstRealW, $dstRealH, $srcW, $srcH);

            if ($data[2] == 3) {
                ImagePNG($dstImg, $dstFile);
            } else {
                ImageJPEG($dstImg, $dstFile, 95);
            }
            
            chmod($dstFile, 0644);
            ImageDestroy($srcImg);
            ImageDestroy($dstImg);
        }
    }

    /**
     * 裁剪缩略图，保留最窄一边内容，剪去长边两侧
     * @param string $srcFile
     * @param string $dstFile
     * @param int $dstW
     * @param int $dstH
     * @author yinzhigang
     */
    static public function crop_thumb($srcFile, $dstFile, $dstW, $dstH) {
        list($srcW, $srcH, $type, $attr) = GetImageSize($srcFile);
        switch ($type) {
            case 1:
                $srcImg = @ImageCreateFromGIF($srcFile);
                break;
            case 2:
                $srcImg = @ImageCreateFromJPEG($srcFile);
                break;
            case 3:
                $srcImg = @ImageCreateFromPNG($srcFile);
                break;
            default:
                return;
                break;
        }
        if ($srcImg) {
            if ($srcW > $srcH) {
                $dstWH = $srcH; //定义原图宽高
                $srcY = 0;
                $srcX = floor(($srcW - $srcH) / 2);
            } else {
                $dstWH = $srcW;
                $srcY = floor(($srcH - $srcW) / 2);
                $srcX = 0;
            }
            
            $dstImg = ImageCreateTrueColor($dstW, $dstH);
            
            imagecopyresampled($dstImg, $srcImg, 0, 0, $srcX, $srcY, $dstW, $dstH, $dstWH, $dstWH);

            ImageJPEG($dstImg, $dstFile, 85);
            chmod($dstFile, 0644);
            ImageDestroy($srcImg);
            ImageDestroy($dstImg);
        }
    }
    /**
     * 裁剪缩略图，根据传递过来的坐标和宽高暗裁剪
     * @param <type> $srcFile 源文件
     * @param <type> $dstFile 保存文件路径
     * @param <type> $dstW
     * @param <type> $dstH
     * @param <type> $dstX
     * @param <type> $dstY
     * @param <type> $proportion  缩略图比例
     * @return <type>
     * @author ly
     */
    static public function cut_pic($srcFile, $dstFile, $dstW, $dstH, $dstX, $dstY,$proportion = array("width"=>200,"height"=>300)){
	list($srcW, $srcH, $type, $attr) = GetImageSize($srcFile);
        switch ($type) {
            case 1:
                $srcImg = @ImageCreateFromGIF($srcFile);
                break;
            case 2:
                $srcImg = @ImageCreateFromJPEG($srcFile);
                break;
            case 3:
                $srcImg = @ImageCreateFromPNG($srcFile);
                break;
            default:
                return;
                break;
        }
        $dstX = $dstX*$srcW/$proportion['width'];
        $dstY = $dstY*$srcH/$proportion['height'];
        $dstW = $dstW*$srcW/$proportion['width'];
        $dstH = $dstH*$srcH/$proportion['height'];

        $newim = imagecreatetruecolor($dstW, $dstH);
        imagecopyresampled($newim, $srcImg, 0, 0, $dstX, $dstY, $dstW, $dstH, $dstW, $dstH);
        imagejpeg($newim, $dstFile);
        imagedestroy($srcImg);
        imagedestroy($newim);

    }
}
