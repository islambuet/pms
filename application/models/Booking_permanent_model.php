<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Booking_permanent_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }
    public function get_list()
    {
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_bookings').' bookings');
        $this->db->select('bookings.*');
        $this->db->select('customers.customer_name');

        $this->db->join($CI->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');

        $results=$CI->db->get()->result_array();
        $bookings=array();
        foreach($results as $result)
        {
            $result['preliminary_booking_date']=System_helper::display_date($result['preliminary_booking_date']);
            $result['permanent_booking_date']=System_helper::display_date($result['permanent_booking_date']);
            $bookings[]=$result;
        }
        return $bookings;

    }

}