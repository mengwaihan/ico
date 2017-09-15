<?php 


class icoModel{
    protected $ico;
    protected $db;
    
    function __construct() {
        $this->ico = $GLOBALS['ico'];
        $this->db = $GLOBALS['db'];

    }

    public function getIncomplete ()
    {
    	$sql = "SELECT *
    			FROM ico_transac
    			WHERE quanlity > 0";
    	return $this->db->getAll($sql);
    }


    public function getLastBuy ()
    {
    	$sql = "SELECT price
    			FROM ico_transac
    			WHERE type = 'eth_cny'
                AND quanlity > 0
    			ORDER BY transac_id DESC";
        $price = $this->db->getOne($sql);
        if (!$price) {
            $sql = "SELECT price
                FROM ico_record
                WHERE type = 'sell_market'
                ORDER BY record_id DESC";
            $price = $this->db->getOne($sql);
        }
		return $this->db->getOne($sql);
    }

    //sell
    public function sell ($data)
    {
    	$sql = "UPDATE ico_transac SET
    			quanlity = :quanlity
    			WHERE transac_id = :transac_id";
    	$this->db->execute($sql, array('quanlity' => 0, 'transac_id' => $data['transac_id']));
    	$data['type'] = 'sell_market';
    	$this->insertRecord($data);
    }

    //buy
    public function buy ($data)
    {
    	$sql = "INSERT INTO ico_transac SET
    			type = :type,
    			price = :price,
    			quanlity = :quanlity,
    			order_id = :order_id";
    	$this->db->execute($sql, $data);
    	$data['transac_id'] = $this->db->lastInsertId();
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
    	$this->db->execute($sql, $data);
    }

    public function getOrderInfo ($orderId)
    {
        $params = array('api_key' => API_KEY, 'symbol' => 'eth_cny', 'order_id' => $orderId);
        $return = $this->ico -> orderInfoApi($params);

        return $return->orders[0];
    }
}
