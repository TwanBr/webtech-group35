<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'users';
        $this->primaryKey = 'id';
    }
    function login($email,$password)
    {
        $this->db->where("email",$email);
        $this->db->where("password",$password);

        $query=$this->db->get("users");
        if($query->num_rows()>0)
        {
            foreach($query->result() as $rows)
            {
                //add all data to session
                $newdata = array(
                    'user_id' 		=> $rows->id,
                    'first_name' 	=> $rows->first_name,
                    'last_name'     => $rows->last_name,
                    'picture_url'     => $rows->picture_url,
                    'email'    => $rows->email,
                    'logged_in' 	=> TRUE,
                );
            }
            $this->session->set_userdata('userdata',$newdata);
        }
        return false;
    }
    public function add_user($data='')
    {
        $data=array(
            'first_name'=>$this->input->post('firstname'),
            'last_name'=>$this->input->post('lastname'),
            'email'=>$this->input->post('email_address'),
            'password'=>md5($this->input->post('password'))
        );
        $this->db->insert('users',$data);
    }
    public function add_userfb($data)
    {
        $this->db->insert('users',$data);
        $this->session->set_userdata('userdata',$data);
        return;
    }
    public function checkUser($data = array()){
        $this->db->select($this->primaryKey);
        $this->db->from($this->tableName);
        $this->db->where(array('oauth_provider'=>$data['oauth_provider'],'oauth_uid'=>$data['oauth_uid']));
        $prevQuery = $this->db->get();
        $prevCheck = $prevQuery->num_rows();

        if($prevCheck > 0){
            $prevResult = $prevQuery->row_array();
            $data['modified'] = date("Y-m-d H:i:s");
            $update = $this->db->update($this->tableName,$data,array('id'=>$prevResult['id']));
            $userID = $prevResult['id'];
        }else{
            $data['created'] = date("Y-m-d H:i:s");
            $data['modified'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert($this->tableName,$data);
            $userID = $this->db->insert_id();
        }
        $this->session->set_userdata('userdata',$data);
        return $userID?$userID:FALSE;
    }
}
?>