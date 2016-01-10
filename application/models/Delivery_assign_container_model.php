<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_assign_container_model extends CI_Model
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

    public function get_containers($consignment_id,$variety_id)
    {
        $CI =& get_instance();


        $this->db->from($CI->config->item('table_container_varieties').' container_varieties');

        $this->db->select('container.id,container.container_name');


        //$this->db->select('container.id container_id,container.container_name');


        //$this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);

        $this->db->join($CI->config->item('table_container').' container','container.id = container_varieties.container_id','INNER');


        //$this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = container_varieties.variety_id','INNER');
        //$this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        //$this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        //$this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');


        $this->db->where('container.consignment_id',$consignment_id);
        $this->db->where('container_varieties.revision',1);
        $this->db->where('container_varieties.variety_id',$variety_id);
        $this->db->order_by('container.id');
        $results=$this->db->get()->result_array();

        return $results;

    }


}