<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model{
	function __construct() {
		$this->tableName = 'users';
		$this->primaryKey = 'id';
	}
	/*public function checkUser($data = array()){
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

		return $userID?$userID:FALSE;
    }*/
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
            //print_r($newdata);exit;
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
        $data['user_id'] = $this->db->insert_id();
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
            $data['user_id'] = $userID;
        }else{
            $data['created'] = date("Y-m-d H:i:s");
            $data['modified'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert($this->tableName,$data);
            $userID = $this->db->insert_id();
            $data['user_id'] = $userID;
        }
        $this->session->set_userdata('userdata',$data);
        return $userID?$userID:FALSE;
    }
    public function wallquery($user_id){
        $query = $this->db->query("SELECT p.*,u.first_name as username,u.picture_url as userpic, pl.like_id from fb_post p left join users u on u.id = p.user_id left join fb_post_likes pl on pl.post_id = p.post_id and pl.user_id = '$user_id' group by p.post_id order by post_id desc");
        return $query;
    }
    public function personalwallquery($user_id){
        $query = $this->db->query("SELECT p.*,u.first_name as username,u.picture_url as userpic, pl.like_id from fb_post p left join users u on u.id = p.user_id left join fb_post_likes pl on pl.post_id = p.post_id and pl.user_id = '$user_id' WHERE p.user_id = '$user_id' group by p.post_id order by post_id desc");
        return $query;
    }
    public function getcommentsquery($post_id){
        $query = $this->db->query("SELECT c.*,u.first_name as username from fb_comment c left join users u on u.id = c.user_id where c.post_id = '$post_id' order by c.comment_id desc");
        return $query;
    }
    public function getallcommentsquery(){
        $query = $this->db->query("SELECT c.*,u.first_name as username from fb_comment c left join users u on u.id = c.user_id order by c.comment_id desc");
        //$sql = $this->db->last_query();
        //echo $sql;exit;
        return $query;
    }
    function deletecomment($id){
        $this->db->where('comment_id', $id);
        $this->db->delete('fb_comment');
    }
    public function addpost($user_id,$content,$date_created,$picturename){
        $query = $this->db->query("INSERT into fb_post(user_id,content,total_like,picturename,date_created) values('$user_id','$content',0,'$picturename','$date_created')");
        return $query;
    }
    public function addPostLike($post_id,$user_id,$date_created){
        $query = $this->db->query("INSERT into fb_post_likes(post_id,user_id,like_date) values('$post_id','$user_id','$date_created')");
        return $query;
    }
    public function updatepostlike($post_id){
        $query = $this->db->query("UPDATE fb_post set total_like = total_like + 1 where post_id = '$post_id'");
        return $query;
    }
    public function addPostdisLike($post_id,$user_id){
        $query = $this->db->query("DELETE from fb_post_likes where user_id = '$user_id' and post_id = '$post_id'");
        return $query;
    }
    public function updatepostdislike($post_id){
        $query = $this->db->query("UPDATE fb_post set total_like = total_like - 1 where post_id = '$post_id'");
        return $query;
    }
    public function addcomment($post_id,$user_id,$comment,$date_created){
        $query = $this->db->query("INSERT into fb_comment(post_id,user_id,comment,comment_date) values('$post_id','$user_id','$comment','$date_created')");
        return $query;
    }
public function invitefriendemail($user_id, $email, $date_created){
    $query = $this->db->query("INSERT into fb_invite(user_id,email,invite_date) values('$user_id','$email','$date_created')");
    return $query;
}

}
