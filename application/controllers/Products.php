<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Manila');
		if(!$this->session->has_userdata('username'))
		{
			redirect('Login','refresh');
		}
		else
		{
			$this->load->model('AdminModel');
			$this->load->model('ProductModel');
		}
	}

	public function index()
	{
		$data['inventory'] = $this->AdminModel->getAllInventory();

		$this->load->view('includes/header');
		$this->load->view('admin/products', $data);
		$this->load->view('includes/footer');
	}

	public function getProductLogs()
	{
		$this->load->library('datatables');
		$this->datatables->select('prod_id as id, prod_name as name, qty, buyer_name as toFrom, date_added as date, dawae as way')
				->from('orders');

		echo $this->datatables->generate('json', '');
	}

	public function testing()
	{
		$this->load->library('datatables');
		$this->datatables->from('inventory');

		echo $this->datatables->generate('json', '');
	}

	public function addStocks()
	{
		$this->load->helper(array('form', 'url'));

		$this->form_validation->set_rules('name', 'name', 'required');
		$this->form_validation->set_rules('qty', 'quantity', 'required|is_natural_no_zero|less_than_equal_to[999999]');
		$this->form_validation->set_rules('supplier', 'supplier', 'required');
		$this->form_validation->set_rules('price', 'price', 'required|greater_than[0]');

		if ($this->form_validation->run() == FALSE)
	    {
			$this->session->set_flashdata('validation_error', "Order Error !!!");
			
			print_r(validation_errors());  	
	    }
	    else
	    {
	    	$id = $this->input->post('name');
			$data = $this->AdminModel->getItemDetails($id);
			$name = $this->AdminModel->getSupplierName($this->input->post('supplier'));
			$contact = $this->AdminModel->getSupplierContact($this->input->post('supplier'));

			$data = array(
	    		'prod_id' => $id,
	    		'prod_name' => $data[0]->name,
	    		'prod_price' => $this->input->post('price'),
	    		'qty' => $this->input->post('qty'),
	    		'dawae' => 'IN',
	    		'buyer_name' => $name,
	    		'contact' => $contact,
	    		'status' => 'Delivered',
		    	'date_added' => date('Y-m-d H:i:s'),
	    		'date_updated' => date('Y-m-d H:i:s')
	    	);
	    	
	        if($this->AdminModel->doAddOrder($data) == 1)
	        {
	        	$this->AdminModel->updateStocks($id, $this->input->post('qty'), 'in');
	        	$this->session->set_flashdata('orderAddSuccess', 'Order Added Successfully !');
	        	echo 1;
	        }
	        else
	        {
	        	$this->session->set_flashdata('orderAddFail', 'Order Add Failed !');
	        	echo 2;
	        }
	    }
	}
}

/* End of file Products.php */
/* Location: ./application/controllers/Products.php */