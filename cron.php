<?php

require_once ('init.php');

//获取当前价格A

$params = array('symbol' => 'eth_cny');
$result = $ico -> tickerApi($params);
// print_r($result);

$A = $result->ticker;
// print_r($A);
// exit;
//循环记录表中未交易完数据，比较现在价格与$A['sell']，差价大于10%（待定），则卖出

foreach ($icoModel->getIncomplete() as $order) {
	if (($order['price'] * (1 + 10 / 100)) < $A->sell) {
		//sell
		$params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'type' => 'sell_market', 'amount' => $order['quanlity']);
		$result = $ico -> tradeApi($params);
		if (isset($result->result) && 'true' == $result->result) {
			$return = $icoModel->getOrderInfo($result->order_id);
			// $params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'order_id' => $result->order_id);
			// $return = $ico -> orderInfoApi(getParam($params));
			$data = array();
			$data = array('quanlity' => $return->amount, 'price' => $return->price, 'transac_id' => $order['transac_id']);
			$icoModel->sell($data);
		}
	}
}

//获取上次买入价格B
$lastBuy = $icoModel->getLastBuy();
//比较$A['buy']与B，差价大于10%（待定），则买入
if (($lastBuy * (1 - 10 / 100)) >= $A->buy){
	//buy
	$params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'type' => 'buy_market', 'price' => (0.1 * $A->buy));
	$result = $ico -> tradeApi($params);
	if (isset($result->result) && 'true' == $result->result) {
		$return = $icoModel->getOrderInfo($result->order_id);
		$data = array();
		$data = array('type' => 'eth_cny', 'quanlity' => $return->amount, 'price' => $return->price, 'order_id' => $result->order_id);
		$icoModel->sell($data);
	}
}