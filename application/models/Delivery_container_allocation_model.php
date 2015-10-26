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
    /*public function get_consignment_info($consignment_id)
    {
        $CI =& get_instance();


        $this->db->from($CI->config->item('table_container_varieties').' container_varieties');
        $this->db->select('container_varieties.variety_id variety_id,container_varieties.quantity');

        $this->db->select('consignment.id consignment_id,consignment.consignment_name,consignment_status,consignment.expected_receive_date');
        //$this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") variety_name',false);
        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);

        $this->db->join($CI->config->item('table_container').' container','container.id = container_varieties.container_id','INNER');
        $this->db->join($CI->config->item('table_consignment').' consignment','consignment.id = container.consignment_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = container_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        $this->db->where('container_varieties.revision',1);
        $this->db->where('consignment.id',$consignment_id);
        $this->db->where('consignment.status',$this->config->item('system_status_active'));
        $this->db->order_by('consignment.id ASC');
        $this->db->order_by('container_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $consignments=array();
        foreach($results as $result)
        {
            if(sizeof($consignments)==0)
            {
                $info=array();
                $info['consignment_id']=$result['consignment_id'];
                $info['consignment_name']=$result['consignment_name'];
                $info['consignment_status']=$result['consignment_status'];
                $info['expected_receive_date']=$result['expected_receive_date'];
                $consignments=$info;
            }
            if(isset($consignments['varieties'][$result['variety_id']]['id']))
            {
                $consignments['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];

            }
            else
            {
                $consignments['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $consignments['varieties'][$result['variety_id']]['variety_name']=$result['variety_name'];
                $consignments['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
            }

        }
        return $consignments;
    }*/
    public function get_booking_info($booking_id)
    {
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_permanent_varieties').' permanent_varieties');
        $this->db->select('permanent_varieties.booking_id booking_id,permanent_varieties.variety_id variety_id,permanent_varieties.quantity');

        //$this->db->select('CONCAT(customer.customer_name,"(",districts.district_name,"-",upazilas.upazila_name,"-",unions.union_name,")") customer_name',false);
        //$this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") customer_name',false);
        //$this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") variety_name',false);
        //$this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = permanent_varieties.booking_id','INNER');
        //$this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        //$this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        //$this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        //$this->db->join($CI->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');

        //$this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = permanent_varieties.variety_id','INNER');
        //$this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        //$this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        //$this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        //$this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');


        $this->db->where('permanent_varieties.revision',1);
        $this->db->where('bookings.id',$booking_id);
        $this->db->order_by('permanent_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $bookings=array();

        foreach($results as $result)
        {

            if(isset($bookings['varieties'][$result['variety_id']]['id']))
            {
                $bookings['varieties'][$result['variety_id']]['quantity']+=$result['quantity'];

            }
            else
            {
                $bookings['varieties'][$result['variety_id']]['id']=$result['variety_id'];
                $bookings['varieties'][$result['variety_id']]['quantity']=$result['quantity'];
            }


        }
        //$bookings=$results;

        return $bookings;

    }
    //public function get_allocated_varieties($year,$booking_id,$consignment_id)
    public function get_allocated_varieties($year,$booking_id)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_allocation_varieties').' allocation_varieties');
        $this->db->where('year',$year);
        $this->db->where('booking_id',$booking_id);
        $this->db->where('revision',1);
        $this->db->order_by('consignment_id ASC');
        $results=$CI->db->get()->result_array();
        $varieties=array();
        foreach($results as $result)
        {
            //$varieties[$result['variety_id']]=$result;
            $varieties[$result['consignment_id']][$result['variety_id']]=$result;
        }
        //$varieties=$results;
        return $varieties;

    }
    public function get_all_allocated_varieties($year)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_allocation_varieties').' allocation_varieties');
        $this->db->where('year',$year);
        $this->db->where('revision',1);
        $results=$CI->db->get()->result_array();
        $varieties=array();
        foreach($results as $result)
        {
            $varieties[$result['booking_id']][$result['consignment_id']][$result['variety_id']]=$result;
        }
        return $varieties;

    }

}