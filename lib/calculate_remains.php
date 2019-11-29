<?php

class calculate_remains {

    /**
     * @param $id_ryad
     * @return int
     * Вычисляем доступный остаток товара в рядах. С учётом количества рядов.
     * На основании этих данных, можно принимать решение, выкупать ряд или нет.
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

    /**
     * @param $id_zp
     * @param $proc
     * @param $ord_user
     * @return float -> $confirmed_order
     * Вычисление суммы только подтвержденный заказов. Отображение в корзине пользователя.
     */
    public function get_confirmed_orders($id_zp, $proc, $ord_user){

        global $DB;

        $sql = "SELECT kolvo, price FROM sp_order
                LEFT JOIN sp_ryad ON sp_ryad.id = sp_order.id_ryad
                WHERE sp_order.id_zp = $id_zp AND sp_order.user = $ord_user AND sp_order.status = 1";
        $response = $DB->getAll($sql);

        $confirmed_order = 0;
        foreach ($response AS $it){
            $proc_result = $it['price'] * $it['kolvo'] / 100 * $proc;
            $res = $it['price'] * $it['kolvo'] + $proc_result;

            $confirmed_order = $confirmed_order + $res;
        };


        return ceil($confirmed_order);
    }
}
