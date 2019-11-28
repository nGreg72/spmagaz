<?php

/**
 * Class db_requests
 */

class db_requests {

    public function get_orders_for_move(){
        $sql = "SELECT sp_order.* FROM sp_order 
        WHERE sp_order.id_zp = 1338";

        global $DB;

        $orders_for_move = $DB->getAll($sql);

        return $orders_for_move;
    }
}
