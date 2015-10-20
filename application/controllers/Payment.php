<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Payment');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='payment';
        //$this->load->model("booking_preliminary_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search($id);
        }
        elseif($action=="list")
        {
            $this->system_list();
        }
        elseif($action=="add")
        {
            $this->system_add($id);
        }
        elseif($action=="edit")
        {
            $this->system_edit($id);
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_search($id);
        }
    }
    public function system_search($booking_id)
    {
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $ajax['status']=true;
            $data['title']="Payment";

            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $data['districts']=array();
            $data['upazilas']=array();
            $data['unions']=array();
            $data['customers']=array();
            $data['booking']['id']=0;
            $data['booking']['year']=Date('Y');
            $data['booking']['customer_id']=0;
            $data['booking']['zone_id']=0;
            $data['booking']['territory_id']=0;
            $data['booking']['district_id']=0;
            $data['booking']['upazila_id']=0;
            $data['booking']['union_id']=0;

            if($booking_id>0)
            {
                $this->db->from($this->config->item('table_bookings').' bookings');
                $this->db->select('bookings.id,bookings.year,bookings.customer_id');

                $this->db->select('territories.zone_id zone_id');
                $this->db->select('districts.territory_id territory_id');
                $this->db->select('upazilas.district_id district_id');
                $this->db->select('unions.upazila_id upazila_id');
                $this->db->select('customers.union_id union_id');
                $this->db->join($this->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');
                $this->db->join($this->config->item('table_unions').' unions','unions.id = customers.union_id','INNER');
                $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
                $this->db->join($this->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
                $this->db->join($this->config->item('table_territories').' territories','territories.id = districts.territory_id','INNER');
                $this->db->where('bookings.id',$booking_id);
                $booking_info=$this->db->get()->row_array();
                if($booking_info)
                {
                    $data['booking']=$booking_info;
                    $data['territories']=Query_helper::get_info($this->config->item('table_territories'),array('id','territory_name'),array('zone_id ='.$data['booking']['zone_id']));
                    $data['districts']=Query_helper::get_info($this->config->item('table_districts'),array('id','district_name'),array('territory_id ='.$data['booking']['territory_id']));
                    $data['upazilas']=Query_helper::get_info($this->config->item('table_upazilas'),array('id','upazila_name'),array('district_id ='.$data['booking']['district_id']));
                    $data['unions']=Query_helper::get_info($this->config->item('table_unions'),array('id','union_name'),array('upazila_id ='.$data['booking']['upazila_id']));
                    $data['customers']=Query_helper::get_info($this->config->item('table_customers'),array('id','customer_name'),array('union_id ='.$data['booking']['union_id']));
                }
            }
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("payment/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search/'.$booking_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_list()
    {
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $customer_id=$this->input->post('customer_id');
            $year=$this->input->post('year');
            $booking_info=Query_helper::get_info($this->config->item('table_bookings'),'*',array('year ='.$year,'customer_id ='.$customer_id),1);
            if($booking_info)
            {
                $booking_id=$booking_info['id'];
                $payments=Query_helper::get_info($this->config->item('table_payments'),'*',array('booking_id ='.$booking_id));

                $ajax['status']=true;
                $data['title']="Payment History";
                $data['payments']=$payments;
                $data['booking_id']=$booking_id;
                $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("payment/list",$data,true));
                $ajax['system_page_url']=site_url($this->controller_url.'/index/search/'.$booking_id);
                $this->jsonReturn($ajax);

            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_BOOKING_NOT_FOUND");
                $this->jsonReturn($ajax);
            }


        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_add($booking_id)
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {

            $this->db->from($this->config->item('table_bookings').' bookings');
            $this->db->select('bookings.id,bookings.year,bookings.customer_id');
            $this->db->select('districts.district_name district_name');
            $this->db->select('upazilas.upazila_name upazila_name');
            $this->db->select('unions.union_name union_name');
            $this->db->select('customers.customer_name customer_name');
            $this->db->join($this->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');
            $this->db->join($this->config->item('table_unions').' unions','unions.id = customers.union_id','INNER');
            $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
            $this->db->join($this->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
            $this->db->where('bookings.id',$booking_id);
            $booking_info=$this->db->get()->row_array();

            if(sizeof($booking_info)>0)
            {
                $ajax['status']=true;
                $data['title']="New Payment for booking id(".$booking_id.")";
                $data['payment']['id']=0;
                $data['payment']['booking_id']=$booking_id;
                $data['payment']['amount']='';
                $data['payment']['payment_method']='';
                $data['payment']['payment_number']='';
                $data['payment']['bank_name']='';
                $data['payment']['branch_name']='';
                $data['payment']['payment_date']=time();
                $data['payment']['remarks']='';

                $data['booking']=$booking_info;

                $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("payment/add_edit",$data,true));
                $ajax['system_page_url']=site_url($this->controller_url.'/index/add/'.$booking_id);
                $this->jsonReturn($ajax);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']='Invalid Booking';
                $this->jsonReturn($ajax);
            }

        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_edit($payment_id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $payment=Query_helper::get_info($this->config->item('table_payments'),'*',array('id ='.$payment_id),1);
            if(sizeof($payment)>0)
            {
                $ajax['status']=true;
                $data['title']="Edit Payment";
                $data['payment']=$payment;

                $this->db->from($this->config->item('table_bookings').' bookings');
                $this->db->select('bookings.id,bookings.year,bookings.customer_id');
                $this->db->select('districts.district_name district_name');
                $this->db->select('upazilas.upazila_name upazila_name');
                $this->db->select('unions.union_name union_name');
                $this->db->select('customers.customer_name customer_name');
                $this->db->join($this->config->item('table_customers').' customers','customers.id = bookings.customer_id','INNER');
                $this->db->join($this->config->item('table_unions').' unions','unions.id = customers.union_id','INNER');
                $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
                $this->db->join($this->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
                $this->db->where('bookings.id',$data['payment']['booking_id']);
                $data['booking']=$this->db->get()->row_array();

                $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("payment/add_edit",$data,true));
                $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$payment_id);
                $this->jsonReturn($ajax);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']='Invalid Payment';
                $this->jsonReturn($ajax);
            }

        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    public function system_save()
    {
        $user=User_helper::get_user();
        $id = $this->input->post("id");
        if($id>0)
        {
            if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['add'])&&($this->permissions['add']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();

            }
        }
        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {

            $data = $this->input->post('payment');
            $data['payment_date']=System_helper::get_time($data['payment_date']);
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_payments'),$data,array("id = ".$id));
                $this->db->trans_complete();   //DB Transaction Handle END

                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_search($data['booking_id']);
                }
                else
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                    $this->jsonReturn($ajax);

                }
            }
            else
            {
                $data['booking_status']=$this->config->item('booking_status_other');
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::add($this->config->item('table_payments'),$data);
                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_search($data['booking_id']);
                }
                else
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                    $this->jsonReturn($ajax);
                }
            }

        }
    }
    private function check_validation()
    {
        $payment=$this->input->post('payment');
        if(!is_numeric($payment['amount']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_MISSING");
            return false;
        }
        /*if(!(($payment['amount'])>0))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_INVALID");
            return false;
        }*/
        if(!($payment['payment_method']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_METHOD_INVALID");
            return false;
        }
        /*if(!($payment['payment_number']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_NUMBER_INVALID");
            return false;
        }*/

        return true;
    }
}
