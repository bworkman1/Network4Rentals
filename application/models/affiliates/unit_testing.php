<?php

/**
 * Created by PhpStorm.
 * User: EMF Brian
 * Date: 12/4/2015
 * Time: 11:40 AM
 */
class Unit_testing extends CI_Model
{
    public function createContractorPayments($data)
    {
        $data = array(
            'year' => (int)$this->uri->segment(4),
            'month' => (int)$this->uri->segment(5),
            'user_id' => (int)$this->uri->segment(6),
            'payments' => (int)$this->uri->segment(7),
            'renewal' => $this->uri->segment(8)
        );
        $date = strtotime($data['year'].'-'.$data['month'].'-01');
        for($i=0;$i<$data['payments'];$i++) {
            $payment_details = array(
                'user_id' => $data['user_id'],
                'amount' => '299.99',
                'type' => 'contractor',
                'payment_id' => 'alkdjf'.rand(1, 200000).'aldsfa',
                'payment_date' => date('Y-m-d H:i', $date),
                'payment_frequency' => 6,
                'sub_id' => rand(1000, 30430404),
                'active' => 'y',
                'expires' => '2016-05-05',
                'last_4' => '4493',
                'renewal' => $data['renewal'],
            );
            $this->db->insert('payments', $payment_details);
            echo $this->db->insert_id().'<br>';
        }

    }



}