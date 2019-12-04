<?php

/**
 * Class dbrequests
 * Разные запросы на чтение в базу данных
 */

class dbrequests {

    /**
     * @param $id_ryad
     * @return int
     * Вычисляем доступный остаток товара в рядах. С учётом количества рядов.
     * На страницах оформления заказа и редактирования (в корзине пользователя) выводим разрешённый доступный остаток
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

    /**
     * @param $id_ryad
     * @param $id_user
     * @return mixed
     * Маркировка заказов, которые входят в полностью заполненные ряды на странице vieworder
     */
    public function is_row_complite($id_ryad, $current_row){

        global  $DB;

        $sql = "SELECT id, name FROM sp_size WHERE id_ryad = $id_ryad and duble = $current_row";
        $sp_size_ids = $DB->getAll($sql);

        $row_lenght = $sp_size_ids[0]['name'];

        $summ = 0;
            foreach ($sp_size_ids as $sp_size_id){
                $id = $sp_size_id['id'];
                $sql = "SELECT kolvo FROM sp_order WHERE id_order = $id";
                $current_qnt_orders = $DB->getOne($sql);

                $summ = $summ + $current_qnt_orders;
            }

        if ($row_lenght == $summ){
            $is_current_row_closed = 1;
        }

    return $is_current_row_closed;
    }

    /**
     * @param $order_id
     * @return int
     * Подкрашиваем квадратики заказов со статусом "Включено в счёт" на странице org/ryad
     */
    public function colorize_order($order_id){

        global $DB;

        $sql = "SELECT status FROM sp_order WHERE id_order = $order_id and status = 1";
        $response = $DB->getOne($sql);

        return $response;
    }

    function move_orders_to_new_row($id_zp, $order_id){

        global $DB;

        $sql = "SELECT id, title FROM sp_ryad WHERE id_zp = $id_zp ORDER BY id";
        $response = $DB->getAll($sql);

        return $response;

    }

}
