<?php
class valite
{
	protected $ImagePath;
	protected $ImageSize;
	protected $ImageMinValue; //阀值。字体色的总和
	protected $BlackImagePath; //黑白图片路径
	protected $FontArray1; //竖切字体路径数组
	protected $FontArray2; //横切字体路径数组

	protected $Fonts; //识别的字符数组。

	public function __construct()
	{
		$this->ImagePath = '';
		$this->ImageSize = '';
		$this->ImageMinValue = '';
		$this->BlackImagePath = '';
		$this->FontArray1 = '';
		$this->FontArray2 = '';
		$this->Fonts = '';
	}

	public function setImage($Image)
	{
		$this->ImagePath = $Image;
	}

	public function getFonts()
	{
		echo implode(',',$this->Fonts);
		echo "\r\n";
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

	public function cutFont()
	{
		$this->cutFont1();

		foreach ($this->FontArray1 as $fontpath) {
			$this->cutFont2($fontpath);
		}
	}

	//图片切割。竖切。不横切。
	public function cutFont1()
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
			if ( ($temp == 0 || $i == $srcsize[0] -1) && $begin_set && !$end_set) {
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
				$font = 'fonts/'.time().rand(1, 50000).'.png';
				imagepng($dstim,$font);
				$begin_set = $end_set = false;
				$begin_point = $end_point = [0,0];
				$this->FontArray1[] = $font;
				imagedestroy($dstim);
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

		// var_dump($srcsize);

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
					if (!$begin_set ) {
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
			//如果设置了起点。没设置终点。$temp变量表示是否出现黑点是否中断。如果起点设置，此事又中断。则设置为终点。
			if ( ($temp == 0 || $i == $srcsize[1] -1) && $begin_set && !$end_set ) {
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
				$font = 'fonts2/'.time().rand(1, 50000).'.png';
				imagepng($dstim,$font);
				$begin_set = $end_set = false;
				$begin_point = $end_point = [0,0];
				$this->FontArray2[] = $font;
			}
		}
	}

	//对比fontlib找出字符。
	public function font2num()
	{	
		$fontlibs = '0123456789abcdefghijklmnopqrstuvwxyz';
		foreach ($this->FontArray2 as $currentCodeFont) {
			$nums = [];
			for ($i=0; $i < strlen($fontlibs); $i++) { 
				$fonts = scandir('fontslib/'.$fontlibs[$i]);

				$temps = []; //某个字符的不同模型的差值数组。
				foreach ($fonts as $font) {
					if (strstr($font, 'png')) {
						$temps[] = $this->diffPointNum('fontslib/'.$fontlibs[$i].'/'.$font,$currentCodeFont);
					}
				}
				if (empty($temps)) {
					continue;
				}else{
					//进行排序。
					sort($temps,SORT_NUMERIC);
					$mintemp = $temps[0];
					$nums[$fontlibs[$i]] = $mintemp;
				}
			}
			asort($nums,SORT_NUMERIC);
			$this->Fonts[] = array_keys($nums)[0];
		}
	}

	//2个图片是否相同。对比像素有多少不同
	//返回不同的像素的数量。
	//第一个参数是字体库的字符模型
	//第二个参数是当前验证码切割的字符。
	public function diffPointNum($dstimg,$srcimg)
	{
		$count = 0 ; 
		# code...
		$dstim = imagecreatefrompng($dstimg);
		$dstsize = getimagesize($dstimg); 

		$srcim = imagecreatefrompng($srcimg);
		$srcsize = getimagesize($srcimg); 

		//先取最小的宽度与高度。
		$width = $srcsize[0];
		$height = $srcsize[1];


		//遍历
		for ($i=0; $i < $width; $i++) { 
			# code...
			for ($j=0; $j < $height; $j++) {

				//切割字符超出的部分。
				if ($i >= $dstsize[0] || $j >= $dstsize[1]) {
				 	# code...
				 	$count++;
				 }else{
					$dstrgb = imagecolorat($dstim,$i,$j);
					$dstrgbarray = imagecolorsforindex($dstim, $dstrgb);

					$srcrgb = imagecolorat($srcim,$i,$j);
					$srcrgbarray = imagecolorsforindex($dstim, $srcrgb);

					if ($dstrgbarray != $srcrgbarray) {
						# code...
						$count++;
					}
				 }
			}
		}
		return $count;
	}
}
?>