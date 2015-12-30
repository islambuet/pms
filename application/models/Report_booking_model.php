<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_booking_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function get_preliminary_booking_report_data()
    {
        $year=$this->input->post('year');
        $crop_id=$this->input->post('crop_id');
        $classification_id=$this->input->post('classification_id');
        $type_id=$this->input->post('type_id');
        $skin_type_id=$this->input->post('skin_type_id');
        $variety_id=$this->input->post('variety_id');
        $zone_id=$this->input->post('zone_id');
        $territory_id=$this->input->post('territory_id');
        $district_id=$this->input->post('district_id');
        $upazila_id=$this->input->post('upazila_id');
        $union_id=$this->input->post('union_id');
        $customer_id=$this->input->post('customer_id');
        $CI =& get_instance();

        $CI =& get_instance();

        $this->db->from($CI->config->item('table_preliminary_varieties').' preliminary_varieties');
        $this->db->select('preliminary_varieties.booking_id booking_id,preliminary_varieties.variety_id variety_id,preliminary_varieties.quantity');

        $this->db->select('bookings.year year,bookings.preliminary_booking_date,bookings.preliminary_remarks');

        $this->db->select('customer.ordering');


        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") customer_name',false);

        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = preliminary_varieties.booking_id','INNER');
        $this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($CI->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($CI->config->item('table_territories').' territories','territories.id = districts.territory_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = preliminary_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        //$this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');

        if($year)
        {
            $this->db->where('bookings.year',$year);
        }
        if($crop_id)
        {
            $this->db->where('classifications.crop_id',$crop_id);
        }
        if($classification_id)
        {
            $this->db->where('classifications.id',$classification_id);
        }
        if($type_id)
        {
            $this->db->where('types.id',$type_id);
        }
        if($skin_type_id)
        {
            $this->db->where('stypes.id',$skin_type_id);
        }
        if($variety_id)
        {
            $this->db->where('varieties.id',$variety_id);
        }
        if($zone_id)
        {
            $this->db->where('territories.zone_id',$zone_id);
        }
        if($territory_id)
        {
            $this->db->where('territories.id',$territory_id);
        }
        if($district_id)
        {
            $this->db->where('districts.id',$district_id);
        }
        if($upazila_id)
        {
            $this->db->where('upazilas.id',$upazila_id);
        }
        if($union_id)
        {
            $this->db->where('unions.id',$union_id);
        }
        if($customer_id)
        {
            $this->db->where('customer.id',$customer_id);
        }
        $this->db->where('preliminary_varieties.revision',1);

        $this->db->order_by('customer.ordering ASC');
        $this->db->order_by('preliminary_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $bookings=array();
        $booking_ids=array();
        foreach($results as $result)
        {

            if(!isset($bookings[$result['booking_id']]))
            {
                $info=array();
                $info['booking_id']=$result['booking_id'];
                $info['customer_name']=$result['customer_name'];
                $info['ordering']=$result['ordering'];
                $info['year']=$result['year'];
                $info['preliminary_booking_date']=$result['preliminary_booking_date'];
                $info['preliminary_remarks']=$result['preliminary_remarks'];
                $bookings[$result['booking_id']]=$info;
                $booking_ids[]=$result['booking_id'];
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
        if(sizeof($booking_ids)>0)
        {
            $this->db->from($this->config->item('table_payments').' payments');
            $this->db->where('booking_status',$CI->config->item('booking_status_preliminary'));
            $this->db->where_in('booking_id',$booking_ids);
            $results=$this->db->get()->result_array();
            foreach($results as $result)
            {
                $bookings[$result['booking_id']]['preliminary_payment_info']=$result;
            }
        }
        //$bookings=$results;


        return $bookings;

    }
    public function get_permanent_booking_report_data()
    {
        $year=$this->input->post('year');
        $crop_id=$this->input->post('crop_id');
        $classification_id=$this->input->post('classification_id');
        $type_id=$this->input->post('type_id');
        $skin_type_id=$this->input->post('skin_type_id');
        $variety_id=$this->input->post('variety_id');
        $zone_id=$this->input->post('zone_id');
        $territory_id=$this->input->post('territory_id');
        $district_id=$this->input->post('district_id');
        $upazila_id=$this->input->post('upazila_id');
        $union_id=$this->input->post('union_id');
        $customer_id=$this->input->post('customer_id');
        $CI =& get_instance();

        $this->db->from($CI->config->item('table_permanent_varieties').' permanent_varieties');
        $this->db->select('permanent_varieties.booking_id booking_id,permanent_varieties.variety_id variety_id,permanent_varieties.quantity,permanent_varieties.unit_price,permanent_varieties.discount');

        $this->db->select('bookings.year year,bookings.permanent_booking_date,bookings.permanent_remarks');

        $this->db->select('customer.ordering');


        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") customer_name',false);

        $this->db->select('CONCAT(varieties.variety_name,"(",classifications.classification_name,")") variety_name',false);


        $this->db->join($CI->config->item('table_bookings').' bookings','bookings.id = permanent_varieties.booking_id','INNER');
        $this->db->join($CI->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($CI->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($CI->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($CI->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($CI->config->item('table_territories').' territories','territories.id = districts.territory_id','INNER');

        $this->db->join($CI->config->item('table_varieties').' varieties','varieties.id = permanent_varieties.variety_id','INNER');
        $this->db->join($CI->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($CI->config->item('table_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($CI->config->item('table_classifications').' classifications','classifications.id = types.classification_id','INNER');

        //$this->db->join($CI->config->item('table_crops').' crops','crops.id = classifications.crop_id','INNER');

        if($year)
        {
            $this->db->where('bookings.year',$year);
        }
        if($crop_id)
        {
            $this->db->where('classifications.crop_id',$crop_id);
        }
        if($classification_id)
        {
            $this->db->where('classifications.id',$classification_id);
        }
        if($type_id)
        {
            $this->db->where('types.id',$type_id);
        }
        if($skin_type_id)
        {
            $this->db->where('stypes.id',$skin_type_id);
        }
        if($variety_id)
        {
            $this->db->where('varieties.id',$variety_id);
        }
        if($zone_id)
        {
            $this->db->where('territories.zone_id',$zone_id);
        }
        if($territory_id)
        {
            $this->db->where('territories.id',$territory_id);
        }
        if($district_id)
        {
            $this->db->where('districts.id',$district_id);
        }
        if($upazila_id)
        {
            $this->db->where('upazilas.id',$upazila_id);
        }
        if($union_id)
        {
            $this->db->where('unions.id',$union_id);
        }
        if($customer_id)
        {
            $this->db->where('customer.id',$customer_id);
        }
        $this->db->where('permanent_varieties.revision',1);
        $this->db->where('bookings.booking_status',$CI->config->item('booking_status_permanent'));

        $this->db->order_by('customer.ordering ASC');
        $this->db->order_by('permanent_varieties.variety_id ASC');
        $results=$CI->db->get()->result_array();
        $bookings=array();
        $booking_ids=array();
        foreach($results as $result)
        {

            if(!isset($bookings[$result['booking_id']]))
            {
                $info=array();
                $info['booking_id']=$result['booking_id'];
                $info['customer_name']=$result['customer_name'];
                $info['ordering']=$result['ordering'];
                $info['year']=$result['year'];
                $info['permanent_booking_date']=$result['permanent_booking_date'];
                $info['permanent_remarks']=$result['permanent_remarks'];
                $info['preliminary_amount']=0;
                $info['permanent_amount']=0;
                $info['other_amount']=0;
                $bookings[$result['booking_id']]=$info;
                $booking_ids[]=$result['booking_id'];
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
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['unit_price']=$result['unit_price'];
                $bookings[$result['booking_id']]['varieties'][$result['variety_id']]['discount']=$result['discount'];
            }


        }
        if(sizeof($booking_ids)>0)
        {
            $this->db->from($this->config->item('table_payments').' payments');
            $this->db->select('amount,booking_status,booking_id');
            $this->db->where_in('booking_id',$booking_ids);
            $results=$this->db->get()->result_array();
            foreach($results as $result)
            {
                if($result['booking_status']==$CI->config->item('booking_status_preliminary'))
                {
                    $bookings[$result['booking_id']]['preliminary_amount']=$result['amount'];
                }
                else if($result['booking_status']==$CI->config->item('booking_status_permanent'))
                {
                    $bookings[$result['booking_id']]['permanent_amount']=$result['amount'];
                }
                else
                {
                    $bookings[$result['booking_id']]['other_amount']+=$result['amount'];
                }

            }
        }
        //$bookings=$results;


        return $bookings;

    }
}