<?php

require_once ('init.php');

//获取当前价格A

$params = array('symbol' => 'eth_cny');
$result = $ico -> tickerApi($params);
// print_r($result);

$A = $result['ticker'];

//循环记录表中未交易完数据，比较现在价格与$A['sell']，差价大于10%（待定），则卖出

foreach ($icoModel->getIncomplete() as $order) {
	if (($order['price'] * (1 + 10 / 100)) < $A['sell']) {
		//sell
		$params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'type' => 'sell_market', 'price' => $order['quanlity']);
		$result = $ico -> tickerApi(getParam($params));
		if ('true' == $result['result']) {
			// $ico->
		} else {

		}
		//
	}
}

//获取上次买入价格B
$lastBuy = $icoModel->getLastBuy();
if (($lastBuy * (1 - 10 / 100)) >= $A['buy']){
	//buy
	$params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'type' => 'buy_market', 'amount' => 0.1);
	$result = $ico -> tickerApi(getParam($params));
	if ('true' == $result['result']) {

	} else {

	}

}

//比较$A['buy']与B，差价大于10%（待定），则买入

//post params
function getParam($params){
	$sign = '';
	foreach ($params as $k => $v) {
		$sign .= $k . '=' . $v . '&';
	}
	$sign .= 'secret_key=' . SECRET_KEY;
	$params['sign'] = md5($sign);
	return $params;
}