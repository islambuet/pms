<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_vehicle_allocation_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }




    public function get_bookings($consignment_id,$variety_id,$container_no)
    {
        $CI =& get_instance();


        $this->db->from($CI->config->item('table_delivery_allocation_varieties').' dav');
        $this->db->select('dav.*');


        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") customer_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = dav.booking_id','INNER');
        $this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');

        $this->db->where('dav.revision',1);
        $this->db->where('dav.consignment_id',$consignment_id);
        $this->db->where('dav.variety_id',$variety_id);
        $this->db->where('dav.container_no',$container_no);

        $this->db->order_by('customer.ordering ASC');

        $results=$CI->db->get()->result_array();
        return $results;
    }

    public function get_allocated_vehicles($consignment_id,$variety_id,$container_no)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_delivery_vehicle_allocation').' va');

        $this->db->where('va.consignment_id',$consignment_id);
        $this->db->where('va.variety_id',$variety_id);
        $this->db->where('va.container_no',$container_no);
        $this->db->where('va.revision',1);
        $results=$this->db->get()->result_array();
        $allocated_vehicles=array();
        foreach($results as $result)
        {
            $allocated_vehicles[$result['booking_id']]=$result;
        }

        return $allocated_vehicles;

    }


}