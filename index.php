<?php 

include 'valite.php';
include 'utils.php';

$valite = new valite();

//获取100个验证码图片。
// for ($i=0; $i < 100; $i++) {

// 	$filename = 'code/'.$i.'.png';
// 	getImg('http://bbs.168hs.com/misc.php?mod=seccode&update=28679&idhash=cSb2oo3f',$filename);
// 	sleep(1);
// }
//分析其中一个图片。取出字模。
// $valite->setImage('code/0.png');
// $num = $valite->getMinHec();
// $valite->toBlackWhite($num);
// 
// $valite->cutFont('code/0-new.png','fonts/0.png');
for ($i=0; $i < 100; $i++) { 
	# code...
	$valite->setImage('codes/'.$i.'.png');
	$valite->getMinHec();
	$valite->toBlackWhite();
	sleep(1);
	$valite->cutFont();
}


 ?>