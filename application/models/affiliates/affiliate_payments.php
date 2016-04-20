<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate_payments extends CI_Model
{
    private $affiliate_id;
    public $type = 'contractor';
    private $yearlyBonusGoal = 260;
    private $magicOverallCommissionPaymentMax = 33;

    public function __construct()
    {
        parent::__construct();
    }

    public function eligibleNewReferralsLastMonth($affiliate_id, $month = null, $year = null)
    {
        $this->affiliate_id = $affiliate_id;
        $data['new'] = $this->eligibleUsersByDates($month, $year);
        $data['renewals'] = $this->getRenewalPayments($month, $year);
        $data['payment_settings'] = $this->getAffiliatePaymentSettings(
            $this->session->userdata('user_id'),
            $month,
            $year
        );
        $data['yearlyData'] = $this->getUserYearToYear();
        return $data;
    }

    private function eligibleUsersByDates($month, $year)
    {
        if ($month == null) {
            $month = date('m');
            $year = date('Y');
        }
        $this->db->select('id, f_name, l_name, created');
        $this->db->from('contractors');
        $this->db->where('MONTH(created)', (int)$month, false);
        $this->db->where('YEAR(created)', (int)$year, false);
        $this->db->where('affiliate_id', $this->affiliate_id);
        $query = $this->db->get();

        if ($query->num_rows()>0) {
            $data = array();

            foreach ($query->result() as $row) {
                $row->payment = $this->getUserPaymentDetails($row->id, $month, $year);
                $data[] = $row;
            }

            return $data;
        } else {
            return false;
        }
    }

    private function getRenewalPayments($month, $year) {
        $this->db->select('contractors.id, contractors.f_name, contractors.l_name,
            contractors.created, payments.amount, payments.payment_date, payments.payment_frequency, payments.renewal');
        $this->db->from('payments');
        $this->db->where('MONTH(payments.payment_date)', (int)$month, false);
        $this->db->where('YEAR(payments.payment_date)', (int)$year, false);
        $this->db->where('contractors.affiliate_id', $this->affiliate_id);
        $this->db->where('payments.type', $this->type);
        $this->db->where('payments.renewal', 'y');
        $this->db->join('contractors', 'contractors.id = payments.user_id');
        $query = $this->db->get();

        return $query->result();
    }

    private function getUserPaymentDetails($user_id, $month, $year)
    {
        $this->db->select('amount, payment_date, payment_frequency');
        $this->db->where('type', $this->type);
        $this->db->where('user_id', $user_id);
        $this->db->where('MONTH(payment_date)', (int)$month, false);
        $this->db->where('YEAR(payment_date)', (int)$year, false);
        $this->db->where('renewal', 'n');
        $query = $this->db->get('payments');

        return $query->row();
    }

    public function getYearlyBonusTotal($affiliate_id, $startDate) {
        $this->affiliate_id = $affiliate_id;

        $this->db->from('payments');
        $this->db->where('payments.payment_date >=', $startDate);
        $this->db->where('renewal', 'n');
        $this->db->where('contractors.affiliate_id', $this->affiliate_id);
        $this->db->join('contractors', 'contractors.id = payments.user_id');
        return $this->db->count_all_results();
    }

    private function getUserYearToYear()
    {
        $userMonthDay = date('m-d', strtotime($this->session->userdata('created')));
        $currentMonthDay = date('m-d');

        if ($userMonthDay>$currentMonthDay) {
            $userMonthDay = date('Y').'-'.$userMonthDay;
            $yearStarts = date('Y-m-d', strtotime($userMonthDay.' -1 year'));
            $yearEnds = date('Y-m-d', strtotime($userMonthDay));
        } else {
            $userMonthDay = date('Y').'-'.$userMonthDay;
            $yearStarts = date('Y-m-d', strtotime($userMonthDay));
            $yearEnds = date('Y-m-d', strtotime($userMonthDay.' +1 year'));
        }

        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime($yearEnds);

        $interval = $datetime1->diff($datetime2);
        $daysLeft = $interval->days;

        $this->db->where(array(
            'created >' => $yearStarts,
            'affiliate_id' => $this->session->userdata('unique_id')
        ));
        $this->db->from('contractors');
        $count = $this->db->count_all_results();

        if($count<$this->yearlyBonusGoal) {
            $count = $this->yearlyBonusGoal - $count;
        }
        $data = array(
            'starts' => date('m-d-Y', strtotime($yearStarts)),
            'ends' => date('m-d-Y', strtotime($yearEnds)),
            'left' => $daysLeft,
            'neededYearly' => $count,
        );
        return $data;
    }

    public function getUserPayments($id, $type, $affiliate_id=null)
    {
        $this->db->select('id, amount, payment_date, payment_frequency, cancel_date, renewal');
        if(!empty($affiliate_id)) {
            $this->db->where('affiliate_id', $affiliate_id);
        }
        $query = $this->db->get_where('payments', array(
            'type' => $type,
            'user_id' => $id
        ));
        if($query->num_rows()>0) {
            return $query->result();
        }
        return false;
    }

    private function getAffiliatePaymentSettings($user_id, $month = null, $year = null)
    {
        $this->db->select(
            'signup_commission, renewal_commission, monthly_bonus, yearly_bonus, yearly_quota, monthly_quota'
        );
        $query = $this->db->get_where('affiliate_users', array('id'=>$user_id));
        $userSettings = $query->row();

        if (!empty($month)&&!empty($year)) {
            $this->db->select('*');
            $this->db->where('MONTH(month_comm_for)', (int)$month, false);
            $this->db->where('YEAR(month_comm_for)', (int)$year, false);
            $this->db->where('affiliate_id', $user_id);
            $query = $this->db->get('affiliate_payments');

            $paymentSentSettings = $query->row();
        }

        $data = array();
        $data['monthly_quota'] = $userSettings->monthly_quota;
        $data['yearly_quota'] = $userSettings->yearly_quota;
        $this->yearlyBonusGoal = $data['yearly_quota'];

        if(!empty($paymentSentSettings)) {
            $data['signup_commission'] = $paymentSentSettings->signup_commission;
            $data['renewal_commission'] = $paymentSentSettings->renewal_commission;
            $data['monthly_bonus'] = $paymentSentSettings->monthly_commission;
            $data['yearly_bonus'] = $paymentSentSettings->yearly_commission;
            $data['address_mailed'] = $paymentSentSettings->address_mailed;
            $data['mailed_on'] = $paymentSentSettings->mailed_on;
            $data['check_mailed'] = $paymentSentSettings->check_mailed;
            $data['amount'] = $paymentSentSettings->amount_paid;
        } else {
            $data['signup_commission'] = $userSettings->signup_commission;
            $data['renewal_commission'] = $userSettings->renewal_commission;
            $data['monthly_bonus'] = $userSettings->monthly_bonus;
            $data['yearly_bonus'] = $userSettings->yearly_bonus;
            $data['address_mailed'] = '';
            $data['mailed_on'] = '';
            $data['check_mailed'] = '';
            $data['amount'] = '';
        }

        return $data;
    }

    /*
     * TEST FUNCTION TO ADD PAYMENT DATA TO TEST IF PERCENTAGES WERE CHANGED
     * */
    public function addAffiliatePayment()
    {
        $data = array(
            'monthly_commission' => '67',
            'yearly_commission' => '33',
            'signup_commission' => '25',
            'renewal_commission' => '15',
            'amount_paid' => '15.99',
            'affiliate_id' => '1',
            'check_mailed' => '0',
            'mailed_on' => '2015-03-01',
            'address_mailed' => '350 Main St, Utica OH',
            'month_comm_for' => '2015-02-01',
        );
        $this->db->insert('affiliate_payments', $data);
    }

    public function getThisMonthCommissionTotals($affiliate_id, $user_id)
    {
        $paymentAmountsNew = array();
        $paymentAmountsRenew = array();
        $totalSignUps = 0;

        $this->db->select('amount, renewal');
        $this->db->where('MONTH(payment_date)', date('m'), false);
        $this->db->where('YEAR(payment_date)', date('Y'), false);
        $query = $this->db->get_where('payments', array('affiliate_id'=>$affiliate_id));
        foreach($query->result() as $row) {
            if($row->renewal == 'y') {
                $paymentAmountsRenew[] = number_format($row->amount, 2);
            } else {
                $paymentAmountsNew[] = number_format($row->amount, 2);
            }
        }

        $totalSignUps = count($paymentAmountsNew);
        $newTotalVolume = array_sum($paymentAmountsNew); //sums all the payments that users have made under affiliate

        $paymentSettings = $this->getAffiliatePaymentSettings($user_id); //gets user commission rates and settings

        $totalAvailableCommission = number_format(
            ($this->magicOverallCommissionPaymentMax / 100) * $newTotalVolume, 2);
        $monthlyCommissionAvailable = number_format(
            ($paymentSettings['signup_commission'] / 100) * $totalAvailableCommission, 2);

        $monthlyBonus = 0;
        if ($totalSignUps >= $paymentSettings['monthly_quota']) {
            //Hit their monthly goal already
            $monthlyBonus = number_format(
                ($paymentSettings['monthly_bonus'] / 100) * ($totalAvailableCommission-$monthlyCommissionAvailable),
                2
            );
            $monthlyCommissionAvailable = $monthlyCommissionAvailable+$monthlyBonus;
        }

        $renewalCommission = 0;
        if(count($paymentAmountsRenew)>0) {
            $renewalVolume = array_sum($paymentAmountsRenew);
            $renewalCommission = number_format(($paymentSettings['renewal_commission'] / 100) * $renewalVolume, 2);
        }

        $data['monthlyBonus'] = $monthlyBonus;
        $data['commission'] = $monthlyCommissionAvailable+$renewalCommission;
        $data['totalSignUps'] = $totalSignUps;

        return $data;
    }

    public function markPaymentPaid($paymentIds)
    {
        $today = date('Y-m-d');
        foreach($paymentIds as $val) {
            $this->db->where('id', $val);
            $this->db->update('payments', array('affiliate_paid_date'=>$today, 'affiliate_paid'=>'y'));
        }
    }

    public function pendingPayments($affiliate_id)
    {
        $this->db->order_by('id', 'asc');
        $this->db->select('id, user_id, amount, type, payment_date, renewal');
        $query = $this->db->get_where('payments', array(
            'affiliate_paid' => 'n',
            'affiliate_id' => $affiliate_id
        ));
        return $query->result();
    }

    public function recentPayments($affiliate_id, $days = 30)
    {
        if($days == 90) {
            $date = date('Y-m-d', strtotime('-90 days'));
        } elseif($days==60) {
            $date = date('Y-m-d', strtotime('-60 days'));
        } else {
            $date = date('Y-m-d', strtotime('-30 days'));
        }
        $this->db->select('id, user_id, amount, type, payment_date, renewal');
        $this->db->where('affiliate_paid_date >', $date);
        $query = $this->db->get_where('payments', array('affiliate_paid' => 'y', 'affiliate_id' => $affiliate_id));
        return $query->result();
    }

}