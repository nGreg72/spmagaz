<?php

$DB=new DB_Engine('mysql', $settings['dbHost'], $settings['dbUser'], $settings['dbPass'], $settings['dbName']);

class calculate_remains {

    /**
     * @param $id_ryad
     * @return int
     * Вычисляем доступный остаток товара в рядах. С учётом количества рядов.
     */
    public function get_remains($id_ryad){

        global $DB;

        $sql = "SELECT sp_size.name FROM sp_size 
        WHERE sp_size.id_ryad = $id_ryad GROUP BY duble";
        $ryad_size = $DB->getAll($sql);

        $sql = "SELECT sp_ryad.duble FROM sp_ryad WHERE sp_ryad.id = $id_ryad";
        $rows_qnt = $DB->getAll($sql);

        $sql = "SELECT sum(sp_order.kolvo) FROM sp_order WHERE sp_order.id_ryad = $id_ryad";
        $orders = $DB->getAll($sql);

        $avail_remains = $ryad_size[0]['name'] * $rows_qnt[0]['duble'] - $orders[0]['sum(sp_order.kolvo)'];

        return $avail_remains;
    }
}