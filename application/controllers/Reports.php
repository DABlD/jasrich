<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use Carbon\Carbon;

class Reports extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Manila');
		if(!$this->session->has_userdata('username'))
		{
			redirect('Login','refresh');
		}
		else
		{
			$this->load->model('AdminModel');
			$this->load->model('ReportModel');
		}
	}

	public function index()
	{
		$this->load->view('includes/header');
		$this->load->view('admin/reports');
		$this->load->view('includes/footer');
	}

	public function getExpiredItems()
	{
		echo json_encode($this->ReportModel->getExpiredItems(Carbon::now()->addMonths('2')->format('Y-m-d')));
	}

	public function getLowStocks()
	{
		echo json_encode($this->ReportModel->getLowStocks());
	}

	public function getInactive()
	{
		$items = $this->ReportModel->getItems();
		$inactives = array();
		$noOrder = array();
		// $valid = Carbon::now()->subMonth()->format('Y-m-d') . ' 00:00:00';
		$valid = Carbon::now()->subMonths('2')->format('Y-m-d') . ' 00:00:00';

		foreach ($items as $item) 
		{
			$last = $this->ReportModel->getLastBought($item->id);
			if($last != null)
			{
				if($last->last < $valid)
				{
					array_push($inactives, array('name' => $last->name, 'last' => $last->last));
				}
			}
			else
			{
				array_push($noOrder, array('name' => $this->ReportModel->getItemName($item->id), 'last' => 'No Order Yet'));
			}
		}

		echo json_encode(array_merge($inactives, $noOrder));
	}

	public function getAuditTrail()
	{
		$this->load->library('datatables');
		$this->datatables->select('*')
				->from('audit_trail');

		echo $this->datatables->generate('json', '');
	}

	public function getSalesReport()
	{
		$start = $this->input->post('start');
		$end = $this->input->post('end');
		$type = $this->input->post('type');

		if($type == 'sales')
		{
			$inDates = array();
			$outDates = array();

			$tempDate = $start;
			while($tempDate < $end)
			{
				$tempIn = $this->ReportModel->getSalesReport($tempDate, 'IN');
				$tempIn == "" ? array_push($inDates, array(0, $tempDate)) : array_push($inDates, array($tempIn, $tempDate));

				$tempOut = $this->ReportModel->getSalesReport($tempDate, 'OUT');
				$tempOut == "" ? array_push($outDates, array(0, $tempDate)) : array_push($outDates, array($tempOut, $tempDate));

				$tempDate = Carbon::parse($tempDate)->addDay()->toDateString();
			}

			$tempIn = $this->ReportModel->getSalesReport($tempDate, 'IN');
			$tempIn == "" ? array_push($inDates, array(0, $tempDate)) : array_push($inDates, array($tempIn, $tempDate));

			$tempOut = $this->ReportModel->getSalesReport($tempDate, 'OUT');
			$tempOut == "" ? array_push($outDates, array(0, $tempDate)) : array_push($outDates, array($tempOut, $tempDate));

			$tempDate = Carbon::parse($tempDate)->addDay()->toDateString();

			echo json_encode(array('in' => $inDates, 'out' => $outDates));
		}
		else
		{
			$categories = $this->AdminModel->getCategories();
			$categorySales = array();

			// foreach ($categories as $category) 
			// {
			// 	$temp = array();
			// 	$tempDate = $start;

			// 	while($tempDate <= $end)
			// 	{
			// 		$categorySaleCount = $this->ReportModel->getCategorySale($category->category, $tempDate);
			// 		array_push($temp, array($categorySaleCount, $tempDate));

			// 		$tempDate = Carbon::parse($tempDate)->addDay()->toDateString();
			// 	}
			// 	array_push($categorySales, $temp);
			// }

			$tempDate = $start;
			while($tempDate <= $end)
			{
				$temp = array();

				foreach ($categories as $category)
				{
					$categorySaleCount = $this->ReportModel->getCategorySale($category->category, $tempDate);
					$categorySaleCount == null ? $categorySaleCount = 0 : '';
					array_push($temp, (int)$categorySaleCount);
				}
				array_push($categorySales, array($tempDate, $temp));
				$tempDate = Carbon::parse($tempDate)->addDay()->toDateString();
			}
			echo json_encode(array('categorySales' => $categorySales, 'categories' => $categories));
		}
	}
}

/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */