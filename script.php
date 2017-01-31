<?php
/**
 * grab answer from zhuhu.com
 */
include './simple_html_dom.php';
$url = 'https://www.zhihu.com/node/QuestionAnswerListV2';

$offset = 20;
$finalResult = array();
$questionId = 22592866; // you question id

do{

	$data['params'] = '{"url_token": '.$questionId.',"pagesize":3,"offset":' . $offset . '}';
	$data['method'] = 'next';

	$respJson = callApiPost($url,$data);

	$resp = json_decode($respJson,true);
	$commentArrTmp = array();

	$commentArrTmp = $resp['msg'];
	foreach ($commentArrTmp as  $commentHtml) {
		$htmlObj = str_get_html($commentHtml);
		$proCount = $htmlObj->find('span',0)->innertext;
		$commentId = substr($htmlObj->find('a',0)->name,7);
		$commentCount = (int)$htmlObj->find('a',9)->plaintext;
		// var_dump($htmlObj->find('div',6)->plaintext);exit;
		$txt = $htmlObj->find('div',7)->plaintext;
		// save data
		insDb($proCount,$commentId,$commentCount,$txt,$commentHtml);
	}

	unset($data,$respJson,$resp);
	$offset += 10;

}while (count($commentArrTmp) > 0 );

echo file_put_contents("./res.txt", json_encode($finalResult));

function callApiPost($url,$data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}


//save data
function insDb($proCount,$commentId,$commentCount,$txt,$commentHtml){

	$con = mysql_connect('localhost:3306','root','vertrigo');

	if(!$con){
		echo 'db err';
		exit;
	}
	mysql_set_charset("UTF8", $con);
	mysql_select_db("test", $con);

	$sql = "insert into zhihu_data (txt,proCount,commentId,commentCount,commentHtml) values ('{$txt}','{$proCount}','{$commentId}','{$commentCount}','{$commentHtml}')";
	// $sql = "insert into zhihu_data (txt,proCount,commentId,commentCount,commentHtml) values ('{txt}','{proCount}','{commentId}','{commentCount}','{commentHtml}' )";
	// file_put_contents("./text.txt", $sql);exit;
	$result = mysql_query($sql, $con);  

	echo $result;

}