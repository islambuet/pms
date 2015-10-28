<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_container_allocation_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }
    public function get_containers($consignment_id)
    {
        $CI =& get_instance();


        $this->db->from($CI->config->item('table_container_varieties').' container_varieties');
        $this->db->select('container_varieties.variety_id variety_id,container_varieties.quantity');


        $this->db->select('container.id container_id,container.container_name');

        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);

        $this->db->join($CI->config->item('table_container').' container','container.id = container_varieties.container_id','INNER');


        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = container_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        $this->db->where('container_varieties.revision',1);
        $this->db->where('container.consignment_id',$consignment_id);

        $this->db->order_by('container.id ASC');
        $this->db->order_by('container_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $containers=array();

        foreach($results as $result)
        {

            if(!isset($containers[$result['container_id']]))
            {
                $info=array();
                $info['container_id']=$result['container_id'];
                $info['container_name']=$result['container_name'];

                $containers[$result['container_id']]=$info;
            }
            if(isset($containers[$result['container_id']]['varieties'][$result['variety_id']]['id']))
            {
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['copy_quantity']=$containers[$result['container_id']]['varieties'][$result['variety_id']]['quantity'];

            }
            else
            {
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['variety_name']=$result['variety_name'];
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
                $containers[$result['container_id']]['varieties'][$result['variety_id']]['copy_quantity']=$result['quantity'];
            }


        }

        //$containers=$results;
        return $containers;

    }
    public function get_bookings($consignment_id)
    {
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_allocation_varieties').' allocation_varieties');
        $this->db->select('allocation_varieties.booking_id booking_id,allocation_varieties.variety_id variety_id,allocation_varieties.quantity');

        $this->db->select('customer.ordering');

        //$this->db->select('CONCAT(customer.customer_name,"(",districts.district_name,"-",upazilas.upazila_name,"-",unions.union_name,")") customer_name',false);
        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") customer_name',false);
        //$this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") variety_name',false);
        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = allocation_varieties.booking_id','INNER');
        $this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        //$this->db->join($CI->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = allocation_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        //$this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');


        $this->db->where('allocation_varieties.revision',1);
        $this->db->where('allocation_varieties.consignment_id',$consignment_id);
        $this->db->order_by('customer.ordering ASC');
        $this->db->order_by('allocation_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $bookings=array();
        foreach($results as $result)
        {

            if(!isset($bookings[$result['booking_id']]))
            {
                $info=array();
                $info['booking_id']=$result['booking_id'];
                $info['customer_name']=$result['customer_name'];
                $info['ordering']=$result['ordering'];
                $bookings[$result['booking_id']]=$info;
            }
            if(isset($bookings[$result['booking_id']]['varieties'][$result['variety_id']]['id']))
            {
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['copy_quantity']=$bookings[$result['booking_id']]['varieties'][$result['variety_id']]['quantity'];

            }
            else
            {
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['variety_name']=$result['variety_name'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['copy_quantity']=$result['quantity'];
            }


        }
        //$bookings=$results;

        return $bookings;

    }

}