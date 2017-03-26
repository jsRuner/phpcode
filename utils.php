<?php 

/*
*@通过curl方式获取指定的图片到本地
*@ 完整的图片地址
*@ 要存储的文件名
*/
function getImg($url = "", $filename = "")
{
 //去除URL连接上面可能的引号
 //$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
 $hander = curl_init();
 $fp = fopen($filename,'wb');
 curl_setopt($hander,CURLOPT_URL,$url);
 curl_setopt($hander,CURLOPT_FILE,$fp);
 // curl_setopt($hander,CURLOPT_HEADER,0);
 curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
 //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
 // curl_setopt($hander, CURLOPT_RETURNTRANSFER, 1); 
 curl_setopt($hander,CURLOPT_TIMEOUT,60);

 curl_setopt($hander, CURLOPT_REFERER, 'http://bbs.168hs.com/member.php?mod=register');

 curl_setopt($hander, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

 curl_setopt($hander,CURLOPT_COOKIE,'D227_de3f_saltkey=dxWLLoPd; D227_de3f_lastvisit=1490229023; UM_distinctid=15af8c943965b7-0de377b3077e2f-1d3b6853-1aeaa0-15af8c943974dd; D227_de3f_atarget=1; D227_de3f_con_request_uri=http%3A%2F%2Fbbs.168hs.com%2Fconnect.php%3Fmod%3Dlogin%26op%3Dcallback%26referer%3Dhttp%253A%252F%252Fbbs.168hs.com%252Fforum-18-1.html; D227_de3f_client_created=1490251898; D227_de3f_client_token=F51B426E245B36BB50704E21C4D12C81; D227_de3f_visitedfid=129D5D151D93; D227_de3f_st_t=0%7C1490364195%7Cbb9d794ae586b077baa30153deb27105; D227_de3f_forum_lastvisit=D_5_1490363889D_129_1490364195; D227_de3f_st_p=0%7C1490364202%7Ccbcf649e8b61a4973bb999f82887fc94; D227_de3f_viewid=tid_670964; D227_de3f_home_diymode=1; D227_de3f_sendmail=1; D227_de3f_seccode=1490.0f1cd44b1e3118eabc; D227_de3f_sid=b2oo3f; CNZZDATA80402=cnzz_eid%3D456834765-1487569417-null%26ntime%3D1490458259; D227_de3f_lastact=1490461013%09misc.php%09seccode');  
  

 curl_exec($hander);
 curl_close($hander);
 fclose($fp);
 Return true;
}

 ?>