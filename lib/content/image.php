<?php
namespace lib\content;
/**
 * @author Karl
 */
class Image {
	static public function thumb($wMax, $hMax, $imgDir, $imgSrc, $flag){
		$thumbDir = 'thumb';
		if(file_exists($imgDir.$thumbDir.'/'.$wMax.'x'.$hMax.'_'.$flag.'_'.$imgSrc)){
			return $imgDir.$thumbDir.'/'.$wMax.'x'.$hMax.'_'.$flag.'_'.$imgSrc;
		}
		else if(file_exists($imgDir.$imgSrc)){
			$imgSrcComplet = $imgDir.$imgSrc;
			$imgSize = GetImageSize($imgSrcComplet); 
			$wSrc = $imgSize[0];
			$hSrc = $imgSize[1];
			$imageType = $imgSize[2];
			$x = 0;
			$y = 0;
	    
		    switch($flag){
		      case 'fit':
		        if($wMax == 0){
		          $wOut = 0;
		          if($hSrc < $hMax){
		            $hOut = $hSrc;
		          }
		          else{
		            $hOut = $hMax;
		          }
		        }
		        if($hMax == 0){
		          $hOut = 0;
		          if($wSrc < $wMax){
		            $wOut = $wSrc;
		          }
		          else{
		            $wOut = $wMax;
		          }
		        }
		        if($wMax != 0 AND $hMax != 0){
		          if($hSrc < $hMax AND $wSrc < $wMax){
		            $hOut = 0;
		            $wOut = 0;
		          }
		          else{
		            $ratio = $wSrc/$hSrc;
		            $hTest = $ratio*($hSrc - $hMax);
		            $wTest = ($wSrc - $wMax);
		            if($hTest == $wTest){
		              $hOut = 0;
		              $wOut = $wMax;
		            }
		            if($hTest < $wTest){
		              $hOut = 0;
		              $wOut = $wMax;
		            }
		            if($hTest > $wTest){
		              $wOut = 0;
		              $hOut = $hMax;
		            }
		          }
		        }
		        if($hOut == 0 AND $wOut ==0){
		          $hOut = $hSrc;
		          $wOut = $wSrc;
		        }
		        elseif($hOut == 0){
		          $hOut = round(($hSrc * $wOut)/$wSrc);
		        }
		        else{
		          $wOut = round(($wSrc * $hOut)/$hSrc);
		        }
		        $newWidth = $wOut;
		        $newHeight = $hOut;
		      break;
		      case 'fill':
		        $rSrc = $wSrc / $hSrc;
		        $rMax = $wMax / $hMax;
		        if ($rSrc > $rMax){
		          $rapport = $hSrc / $hMax;
		          $x += round(($wSrc - $wMax * $rapport) / 2);
		        }
		        else{
		          $rapport = $wSrc / $wMax;                   
		          $y += round(($hSrc - $hMax * $rapport) / 2);
		        }
		        $wOut = round($wSrc / $rapport);
		        $hOut = round($hSrc / $rapport);
		        $newWidth = $wMax;
		        $newHeight = $hMax;
		      break;
		      default:
		        return null;
		    }
	    
			if(!is_dir($imgDir.$thumbDir)){
	      mkdir($imgDir.$thumbDir);
			}
	  
			if($imageType == 2){
				$imgIn = imagecreatefromjpeg($imgSrcComplet);
				$imgOut = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($imgOut, $imgIn, 0, 0, $x, $y, $wOut, $hOut, $wSrc, $hSrc);
				imagejpeg($imgOut, $imgDir.$thumbDir.'/'.$wMax.'x'.$hMax.'_'.$flag.'_'.$imgSrc, 95);
				imagedestroy($imgOut);
			}
			else if($imageType == 3){
				$imgIn = imagecreatefrompng($imgSrcComplet);
				$imgOut = imagecreatetruecolor($newWidth, $newHeight);
				imagecopyresampled($imgOut, $imgIn, 0, 0, $x, $y, $wOut, $hOut, $wSrc, $hSrc);
				imagepng($imgOut, $imgDir.$thumbDir.'/'.$wMax.'x'.$hMax.'_'.$flag.'_'.$imgSrc, 8);
				imagedestroy($imgOut);
			}
			return $imgDir.$thumbDir.'/'.$wMax.'x'.$hMax.'_'.$flag.'_'.$imgSrc;
		}
	}
}

?>