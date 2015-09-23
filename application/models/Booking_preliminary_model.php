<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Booking_preliminary_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }
    public function get_all_varieties()
    {
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_varieties').' varieties');
        //$this->db->select('varieties.id id,varieties.variety_name variety_name,varieties.unit_price');
        //$this->db->select('varieties.remarks remarks,varieties.status status,varieties.ordering ordering');
        //$this->db->select('crops.crop_name crop_name');
        //$this->db->select('classifications.classification_name classification_name');
        //$this->db->select('types.type_name type_name');
        //$this->db->select('stypes.skin_type_name skin_type_name');
        $this->db->select('varieties.id id');
        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,"-",types.type_name,"-",stypes.skin_type_name,")") text',false);

        $this->db->join($CI->config->item('table_crops').' crops','crops.id = varieties.crop_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = varieties.classification_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =varieties.type_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->where('varieties.status !=',$CI->config->item('system_status_delete'));
        $this->db->order_by('varieties.ordering');
        $varieties=$CI->db->get()->result_array();

        return $varieties;

    }
    public function  get_variety_prices($variety_ids)
    {
        $CI =& get_instance();
        $this->db->from($CI->config->item('table_varieties').' varieties');
        $this->db->select('id,unit_price');
        $this->db->where_in('id',$variety_ids);
        $results=$CI->db->get()->result_array();
        $varieties=array();
        foreach($results as $result)
        {
            $varieties[$result['id']]=$result;
        }

        return $varieties;

    }
}