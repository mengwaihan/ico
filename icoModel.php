<?php 


class iocModel(){

    public function getIncomplete ()
    {
    	$sql = "SELECT *
    			FROM ico_transac
    			WHERE quanlity > 0";
    	return $db->getAll($sql);
    }


    public function getLastBuy ()
    {
    	$sql = "SELECT price
    			FROM ico_record
    			WHERE type = buy_market
    			ORDER BY record_id DESC";
		return $db->getOne($sql);
    }

    //buy
    public function sell ($data)
    {
    	$sql = "UPDATE ico_transac SET
    			quanlity = :quanlity
    			WHERE transac_id = :transac_id";
    	$db->execute($sql, array('quanlity' => $data['quanlity'], 'transac_id' => $data['transac_id']));
    	$data['type'] = 'sell_market';
    	$this->insertRecord($data);
    }

    //sell
    public function buy ($data)
    {
    	$sql = "INSERT INTO ico_transac SET
    			type = :type,
    			price = :price,
    			quanlity = :quanlity,
    			order_id = :order_id";
    	$db->execute($sql, $data);
    	$data['transac_id'] = $db->lastInsertId();
    	unset($data['order_id']);
    	$data['type'] = 'buy_market';
    	$this->insertRecord($data);
    }


    public function insertRecord ($data)
    {
    	$sql = "INSERT INTO ico_record SET
    			type = :type,
    			quanlity = :quanlity,
    			price = :price,
    			transac_id = :transac_id";
    	$db->execute($sql, $data);
    }
}