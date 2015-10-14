<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Allocation_consignment_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }
    public function get_bookings($year)
    {
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_permanent_varieties').' permanent_varieties');
        $this->db->select('permanent_varieties.booking_id booking_id,permanent_varieties.variety_id variety_id,permanent_varieties.quantity');

        $this->db->select('CONCAT(customer.customer_name,"(",districts.district_name,"-",upazilas.upazila_name,"-",unions.union_name,")") customer_name',false);
        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") variety_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = permanent_varieties.booking_id','INNER');
        $this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($CI->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = permanent_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        //$this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');


        $this->db->where('permanent_varieties.revision',1);
        $this->db->where('bookings.year',$year);
        $this->db->order_by('permanent_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $bookings=array();
        foreach($results as $result)
        {

            if(!isset($bookings[$result['booking_id']]))
            {
                $info=array();
                $info['booking_id']=$result['booking_id'];
                $info['customer_name']=$result['customer_name'];
                $bookings[$result['booking_id']]=$info;
            }
            if(isset($bookings[$result['booking_id']]['varieties'][$result['variety_id']]['id']))
            {
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];

            }
            else
            {
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['variety_name']=$result['variety_name'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
            }


        }
        //$bookings=$results;

        return $bookings;

    }
    public function get_consignments($year)
    {
        $CI =& get_instance();


        $this->db->from($CI->config->item('table_container_varieties').' container_varieties');
        $this->db->select('container_varieties.variety_id variety_id,container_varieties.quantity');

        $this->db->select('consignment.id consignment_id,consignment.consignment_name');
        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") variety_name',false);

        $this->db->join($CI->config->item('table_container').' container','container.id = container_varieties.container_id','INNER');
        $this->db->join($CI->config->item('table_consignment').' consignment','consignment.id = container.consignment_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = container_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        $this->db->where('container_varieties.revision',1);
        $this->db->where('consignment.year',$year);
        $this->db->where('consignment.status',$this->config->item('system_status_active'));
        $this->db->order_by('container_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $consignments=array();
        foreach($results as $result)
        {

            if(!isset($consignments[$result['consignment_id']]))
            {
                $info=array();
                $info['consignment_id']=$result['consignment_id'];
                $info['consignment_name']=$result['consignment_name'];
                $consignments[$result['consignment_id']]=$info;
            }
            if(isset($consignments[$result['consignment_id']]['varieties'][$result['variety_id']]['id']))
            {
                $consignments[$result['consignment_id']]['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];

            }
            else
            {
                $consignments[$result['consignment_id']]['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $consignments[$result['consignment_id']]['varieties'][$result['variety_id']]['variety_name']=$result['variety_name'];
                $consignments[$result['consignment_id']]['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
            }


        }
        //$consignments=$results;

        return $consignments;

    }
}