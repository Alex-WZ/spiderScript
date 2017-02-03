<?php
/**
 *  抓取煎蛋图片
 */
include './simple_html_dom.php';

$logDir = './catchJandanImgLog.log';

//无聊图按年份归档
// $startPage = 9400;
// $endPage = 9487;
// $year = '-2016'
// $baseDir = './img/pic/';
// $baseUrl = 'http://jandan.net/pic' . $year . '/page-';

//xxoo图 你懂的
$startPage = 1711;
$endPage = 1943;
$baseDir = './img/xxoo/';
$baseUrl = 'http://jandan.net/ooxx/page-';

$cur = $startPage;

$htmlObj = new simple_html_dom();

while($cur <= $endPage){
	$url = $baseUrl . $cur;

	$htmlObj = file_get_html($url);
	if(false == $htmlObj){
		echo "can't get content from server \r\n";
		exit;
	}

	// simple_html_dom class seletor disable
	// $imgTags = $htmlObj->find('a.view_img_link');
	
	$imgTags = $htmlObj->find('a');

	//when server deny accesss
	if(null == $htmlObj){
		echo "can't get content from server\r\n";
		sleep(60);
		$htmlObj = file_get_html($url);
	}

	foreach ($imgTags as $imgTag) {

		$imgUrl = 'http:' . $imgTag->href;
		$temp = explode(".", $imgUrl);
		$imgType = $temp[count($temp) - 1];

		// distinct img type, skip other 
		if($imgType == 'jpg'){
			$imgTypeDir = 'jpg/';
		}else if($imgType == 'gif'){
			$imgTypeDir = 'gif/';
		}else{
			continue;
		}

		echo 'get img from:' . $url;

		$fileName =  (string)microtime(1) . '.' . $imgType;

		echo ' --- img:' . $fileName . "\r\n";

		if(!is_dir($baseDir . $imgType)){
			// echo $baseDir . $imgType;exit;
			$dirRes = mkdir($baseDir . $imgType, 755, true);
			if(!$dirRes){
				echo 'make dir error';
				exit;
			}

		}
		$getResult = file_put_contents($baseDir . $imgTypeDir . $fileName, file_get_contents($imgUrl));
		echo  " write " . $getResult . PHP_EOL;

		//log
		$recordRes = $getResult ? 'success' : 'fail';
		echo ' --- result :' . $recordRes . "\r\n";
		file_put_contents($logDir, 'file :' . $fileName . 'save result ->' . $recordRes . "\r\n",FILE_APPEND);

		unset($imgTag,$imgUrl,$temp,$imgType,$fileName,$getResult,$recordRes);
	}

	sleep(5);

	$cur ++;
	$htmlObj->clear();

	unset($imgTags);
}
?>