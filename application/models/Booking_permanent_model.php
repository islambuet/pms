<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Booking_permanent_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }
    public function get_all_variety_price($year)
    {
        $CI =& get_instance();
        $results=System_helper::get_all_varieties_for_dropdown();
        $varieties=array();
        foreach($results as $result)
        {
            $varieties[$result['id']]=$result;
            $varieties[$result['id']]['unit_price']=0;
        }
        $this->db->from($CI->config->item('table_variety_price').' vp');
        $this->db->select('variety_id ,unit_price');
        $this->db->where('revision',1);
        $this->db->where('year',$year);
        $results=$this->db->get()->result_array();
        if(sizeof($results)!=sizeof($varieties))
        {
            return false;
        }
        foreach($results as $result)
        {
            $varieties[$result['variety_id']]['unit_price']=$result['unit_price'];
        }

        return $varieties;
    }
    /*public function get_booking_info($id)
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

        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');
        $this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');

        $this->db->where('bv.revision',1);
        $this->db->where('bv.booking_id',$booking_id);
        $results=$this->db->get()->result_array();
        return $results;
    }*/

}