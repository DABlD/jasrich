<?php

class LoginModel extends CI_Model {


        public function checkAccount1($username, $password)
        {
        	//$query = $this->db->query("YOUR QUERY");
        	//foreach ($query->result() as $row)
        	//$query->num_rows();
        	
        	$this->db->where('username', $username);
        	$this->db->where('password', md5($password));
        	$status = $this->db->get('users')->row()->deleted_at;
        	
        	if($status != '')
        	{
        	    return 'disabled';
        	}
        	else
        	{
            	$this->db->where('username',$username);
            	$this->db->where('password',md5($password));
    
                $query = $this->db->select('role, id')->get('users');
    
                if($query->num_rows() > 0)
                {
                        $data = array(
                                'username' => $username,
                                'action' => 'Logged in',
                        );
    
                        $this->db->insert('audit_trail', $data);
    
                	return $query->row_array();
                }
                else
                {
                	return false;
                }
        	}
        }
}
