<?php 

include 'valite.php';
include 'utils.php';

error_reporting(0);
//获取100个验证码图片。
// for ($i=0; $i < 100; $i++) {
// 	$filename = 'codes/'.$i.'.png';
// 	getImg('http://bbs.168hs.com/misc.php?mod=seccode&update=28679&idhash=cSb2oo3f',$filename);
// 	sleep(1);
// }
//识别验证码并输出内容。
for ($i=0; $i < 10; $i++) { 
	# code...
	$valite = new valite();
	$valite->setImage('codes/'.$i.'.png');
	$valite->getMinHec();
	$valite->toBlackWhite();
	$valite->cutFont();
	$valite->font2num();
	$valite->getFonts();
	sleep(1);
}
 ?>