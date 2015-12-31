<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_invoice extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Payment_invoice');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='payment_invoice';
        //$this->load->model("allocation_consignment_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="booking_list")
        {
            $this->system_booking_list();
        }
        elseif($action=="invoice")
        {
            $this->system_invoice();
        }
        else
        {
            $this->system_search();
        }
    }

    public function system_search()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Generate Invoice";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("payment_invoice/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_booking_list()
    {
        $year=$this->input->post('year');
        $this->db->from($this->config->item('table_bookings').' bookings');
        $this->db->join($this->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($this->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');

        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") text',false);
        $this->db->select('bookings.id value');
        $this->db->where('bookings.year',$year);
        $data['items']=$this->db->get()->result_array();
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#booking_id","html"=>$this->load->view("dropdown_with_select",$data,true));
        $this->jsonReturn($ajax);
    }
    public function system_invoice()
    {
        $year=$this->input->post('year');
        $booking_id=$this->input->post('booking_id');
        $invoice_date=$this->input->post('invoice_date');
        $data['invoice_date']=$invoice_date;
        $data['setup_invoice']=Query_helper::get_info($this->config->item('table_setup_invoice'),'*',array('revision =1'),1);
        $data['payments']=Query_helper::get_info($this->config->item('table_payments'),'*',array('booking_id ='.$booking_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("payment_invoice/invoice",$data,true));
        $this->jsonReturn($ajax);
    }
}
