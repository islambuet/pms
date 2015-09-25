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
        $this->db->from($CI->config->item('table_booked_varieties').' bv');
        $this->db->select('SUM(bv.quantity) total_quantity',false);
        $this->db->select('SUM(bv.unit_price*bv.quantity) total_price',false);
        $this->db->select('bv.booking_id');
        $this->db->group_by('bv.booking_id');
        $this->db->where('bv.revision',1);
        $results=$this->db->get()->result_array();
        $booked_varieties=array();
        foreach($results as $result)
        {
            $result['preliminary_payment']=0;
            $result['permanent_payment']=0;
            $booked_varieties[$result['booking_id']]=$result;

        }

        $this->db->from($CI->config->item('table_booking_payments').' bp');
        $this->db->select('bp.booking_id,bp.amount,bp.booking_status');
        $results=$this->db->get()->result_array();
        foreach($results as $result)
        {
            if($result['booking_status']==$CI->config->item('booking_status_preliminary'))
            {
                $booked_varieties[$result['booking_id']]['preliminary_payment']=$result['amount'];
            }
            elseif($result['booking_status']==$CI->config->item('booking_status_permanent'))
            {
                $booked_varieties[$result['booking_id']]['permanent_payment']=$result['amount'];
            }


        }

        $this->db->from($CI->config->item('table_bookings').' bookings');
        $this->db->select('bookings.*');
        $this->db->select('customers.customer_name');

        $this->db->join($CI->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');

        $results=$this->db->get()->result_array();
        $bookings=array();
        foreach($results as $result)
        {
            $result['total_quantity']=$booked_varieties[$result['id']]['total_quantity'];
            $result['total_price']=$booked_varieties[$result['id']]['total_price'];
            $result['preliminary_payment']=$booked_varieties[$result['id']]['preliminary_payment'];
            $result['permanent_payment']=$booked_varieties[$result['id']]['permanent_payment'];
            $result['total_payment']=$result['preliminary_payment']+$result['permanent_payment'];
            $result['preliminary_booking_date']=System_helper::display_date($result['preliminary_booking_date']);
            $result['permanent_booking_date']=System_helper::display_date($result['permanent_booking_date']);
            $bookings[]=$result;
        }
        return $bookings;

    }
    public function get_booking_info($id)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_bookings').' bookings');
        $this->db->select('bookings.*');
        $this->db->select('customers.customer_name');
        $this->db->join($CI->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');
        $this->db->where('bookings.id',$id);
        $result=$this->db->get()->row_array();
        return $result;
    }
    public function get_preliminary_payment($id)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_booking_payments').' bp');
        $this->db->select('bp.*');
        $this->db->where('booking_id',$id);
        $this->db->where('booking_status',$CI->config->item('booking_status_preliminary'));
        $results=$this->db->get()->row_array();
        return $results;
    }
    public function get_permanent_payment($id)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_booking_payments').' bp');
        $this->db->select('bp.*');
        $this->db->where('booking_id',$id);
        $this->db->where('booking_status',$CI->config->item('booking_status_permanent'));
        $results=$this->db->get()->row_array();
        return $results;
    }
    public function get_booked_varieties($booking_id)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_booked_varieties').' bv');
        $this->db->select('bv.*');
        $this->db->select('CONCAT(varieties.variety_name," (",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") text',false);

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = bv.variety_id','INNER');

        $this->db->join($CI->config->item('table_crops').' crops','crops.id = varieties.crop_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = varieties.classification_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =varieties.type_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->where('bv.revision',1);
        $this->db->where('bv.booking_id',$booking_id);
        $results=$this->db->get()->result_array();
        return $results;
    }

}