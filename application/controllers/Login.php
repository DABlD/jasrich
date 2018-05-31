<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->helper('url');
		$this->load->library('session');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('id');
	}

	public function index()
	{
		$this->load->view('login/loginpage');
	}
	
	public function checkAccount()
	{
		$username = $this->input->post("username");
		$password= $this->input->post("password");

		$result = $this->LoginModel->checkAccount1($username, $password);

        // echo $result == true . '<br>' . $result == 'disabled';
		if($result == 'disabled')
		{
			$this->session->set_flashdata('disabledAccount', 'Your Account is Disabled !');
			redirect('Login');
		}
		elseif($result == true)
		{
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('role', $result['role']);
			$this->session->set_userdata('id', $result['id']);
			redirect(ucfirst($this->session->role) . '/home');
		}
		else
		{
			$this->session->set_flashdata('loginStatus', 'Invalid Login !');
			redirect('Login');
		}
	}
}
