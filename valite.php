<?php
class valite
{
	protected $ImagePath;
	protected $ImageName;
	protected $ImageSize;
	protected $ImageMinValue; //阀值。字体色的总和
	protected $BlackImagePath; //黑白图片路径
	protected $FontImagePath; //字体路径

	public function setImage($Image)
	{
		$this->ImagePath = $Image;
	}
	//获取阀值。
	public function getMinHec()
	{
		$res = imagecreatefrompng($this->ImagePath);
		$size = getimagesize($this->ImagePath);
		$data = array();
		for($i=0; $i < $size[1]; ++$i)
		{
			for($j=0; $j < $size[0]; ++$j)
			{
				$rgb = imagecolorat($res,$j,$i);
				$rgbarray = imagecolorsforindex($res, $rgb);

				$temp = $rgbarray['red'] +$rgbarray['green']+ $rgbarray['blue'];

				$data[] = $temp;
			}
		}
		$this->ImageMinValue =  min($data);
	}

	//图片黑百化
	public function toBlackWhite()
	{
		# code...
		$res = imagecreatefrompng($this->ImagePath);
		$size = getimagesize($this->ImagePath); //0是宽 1是高

		//制作画布。
		$img = imagecreatetruecolor($size[0],$size[1]);

		$data = array();
		for($i=0; $i < $size[0]; ++$i)
		{
			for($j=0; $j < $size[1]; ++$j)
			{

				$rgb = imagecolorat($res,$i,$j);
				$rgbarray = imagecolorsforindex($res, $rgb);
				$temp  = $rgbarray['red'] +$rgbarray['green']+ $rgbarray['blue'];
				if( $temp == $this->ImageMinValue )
				{
					$color = imagecolorallocate($img, 0, 0, 0);
				}else{
					$color = imagecolorallocate($img, 255, 255, 255);
				}
				imagesetpixel($img, $i, $j,$color);
			}
		}

		//获取文件名。先查找/ 然后去掉.png 即可。
		$p = strrpos($this->ImagePath,'/');
		$this->ImageName = substr($this->ImagePath, $p+1);

		imagepng($img,'blackcodes/black'.$this->ImageName);
		imagedestroy($img);
		$this->BlackImagePath = 'blackcodes/black'.$this->ImageName;
	}

	//图片切割。竖切。不横切。
	public function cutFont()
	{
		$srcim = imagecreatefrompng($this->BlackImagePath);
		$srcsize = getimagesize($this->BlackImagePath); //0是宽 1是高

		//起点与终点的状态
		$begin_set = false;
		$end_set = false;

		$begin_point = [0,0]; 
		$end_point = [0,0];

		for ($i=0; $i < $srcsize[0]; $i++) {
			$temp = 0;
			for ($j=0; $j < $srcsize[1]; $j++) { 
				//获取颜色。
				$rgb = imagecolorat($srcim,$i,$j);
				$rgbarray = imagecolorsforindex($srcim, $rgb);

				//如果是黑色。
				if ($rgbarray['red'] == 0 && $rgbarray['green'] == 0 && $rgbarray['blue'] ==0) {
					 //如果起点没设置。则设置这个点为起点。
					if (!$begin_set) {
					 	# code...
					 	$begin_point= [$i,0];
					 	$begin_set = true;
					}
					$temp++;
					break;
				}else{
					continue;
				}
			}
			//如果设置了起点。没设置终点。
			if ($temp == 0 && $begin_set && !$end_set) {
				# code...
				$end_point =[$i,0];
				$end_set = true;
			}
			//如果都设置了.则切割。
			if ($begin_set && $end_set) {
				# code...
				$dstim = imagecreatetruecolor($end_point[0]-$begin_point[0],$srcsize[1]);
				$colBG = imagecolorallocate($dstim, 255, 255, 255);//白色背景
				imagefill( $dstim, 0, 0, $colBG );//加白色背景
				imagecopyresized($dstim, $srcim, 0,0, $begin_point[0], 0,$end_point[0]-$begin_point[0],$srcsize[1],$end_point[0]-$begin_point[0],$srcsize[1]);
				imagepng($dstim,'fonts/'.time().random_int(1, 50000).'.png');
				$begin_set = $end_set = false;
				$this->FontImagePath = 'fonts/'.time().random_int(1, 50000).'.png';
				sleep(1);
				$this->cutFont2($this->FontImagePath);
			}
		}
	}

	//横切。将fonts中横切放到fonts2
	public function cutFont2($fontpath)
	{
		# code...
		$srcim = imagecreatefrompng($fontpath);
		$srcsize = getimagesize($fontpath); //0是宽 1是高

		//起点与终点的状态
		$begin_set = false;
		$end_set = false;

		$begin_point = [0,0]; 
		$end_point = [0,0];

		//i是高，j是横
		for ($i=0; $i < $srcsize[1]; $i++) {
			$temp = 0;
			for ($j=0; $j < $srcsize[0]; $j++) { 
				//获取颜色。
				$rgb = imagecolorat($srcim,$j,$i);
				$rgbarray = imagecolorsforindex($srcim, $rgb);

				//如果是黑色。
				if ($rgbarray['red'] == 0 && $rgbarray['green'] == 0 && $rgbarray['blue'] ==0) {
					 //如果起点没设置。则设置这个点为起点。
					if (!$begin_set) {
					 	# code...
					 	$begin_point= [0,$i];
					 	$begin_set = true;
					}
					$temp++;
					break;
				}else{
					continue;
				}
			}
			//如果设置了起点。没设置终点。
			if ($temp == 0 && $begin_set && !$end_set) {
				# code...
				$end_point =[0,$i];
				$end_set = true;
			}
			//如果都设置了.则切割。
			if ($begin_set && $end_set) {
				$dstim = imagecreatetruecolor($srcsize[0],$end_point[1]-$begin_point[1]);
				$colBG = imagecolorallocate($dstim, 255, 255, 255);//白色背景
				imagefill( $dstim, 0, 0, $colBG );//加白色背景

				imagecopyresized($dstim, $srcim, 0,0,0,$begin_point[1],$srcsize[0],$end_point[1]-$begin_point[1],$srcsize[0],$end_point[1]-$begin_point[1]);

				imagepng($dstim,'fonts2/'.time().random_int(1, 50000).'.png');
				$begin_set = $end_set = false;
			}
		}
	}
	
}
?>