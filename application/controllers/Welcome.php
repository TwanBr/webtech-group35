<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    //public function index()
    //{
    //	$this->load->view('welcome_message');
    //}
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
    }

    public function index()
    {
        // Include the facebook api php libraries
        include_once APPPATH . "libraries/facebook-api-php/facebook.php";

        // Facebook API Configuration
        $appId = '1835977513283985';
        $appSecret = 'c0a6cdbd503becdcd303f8b93a51dd41';
        $redirectUrl = base_url();
        $fbPermissions = 'email';

        //Call Facebook API
        $facebook = new Facebook(array(
            'appId' => $appId,
            'secret' => $appSecret

        ));
        $fbuser = $facebook->getUser();

        if ($fbuser) {
            $userProfile = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture.type(large)');
            // Preparing data for database insertion
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid'] = $userProfile['id'];
            $userData['first_name'] = $userProfile['first_name'];
            $userData['last_name'] = $userProfile['last_name'];
            $userData['email'] = $userProfile['email'];
            $userData['gender'] = $userProfile['gender'];
            $userData['locale'] = $userProfile['locale'];
            $userData['profile_url'] = 'https://www.facebook.com/' . $userProfile['id'];
            $userData['picture_url'] = $userProfile['picture']['data']['url'];
            // Insert or update user data
            $userID = $this->user->checkUser($userData);
            if (!empty($userID)) {
                $data['userData'] = $userData;
                //$this->welcome($data);
            } else {
                $this->user->add_userfb($userData);
            }
        } else {
            $fbuser = '';
            $data['authUrl'] = $facebook->getLoginUrl(array('redirect_uri' => $redirectUrl, 'scope' => $fbPermissions));
        }

        if (($this->session->userdata('userdata') != "")) {
            $this->welcome($data);
        } else {
            $data['title'] = 'Home';
            $this->load->view("registration_view.php", $data);
        }
    }

    public function welcome($data = '')
    {

        $action = $this->input->post('action');
        $postfield = $this->input->post('post_feed');
        if (isset($_POST['action']) && $_POST['action'] != '') {
            switch ($_POST['action']) {
                case 'post':
                    $this->submitWallPost($_POST);
                    break;

                case 'like':
                    $this->submitPostLike($_POST);
                    break;

                case 'dislike':
                    $this->submitPostDisLike($_POST);
                    break;

                case 'comment':
                    $this->submitPostComment($_POST);
                    break;

                default:
                    return false;
                    break;
            }
        }
        if (($this->session->userdata('userdata') == "")) {
            $this->index();
        } else {
            $postdatam = $this->getWallPost();
//print_r($postdatam);exit;
            $data['comments'] = $this->getallcommentsforposts();

            $data['postdata'] = $postdatam;
            $data['title'] = 'Welcome';
            $this->load->view('header_view', $data);
            $this->load->view('welcome_view.php', $data);
            $this->load->view('footer_view', $data);
        }
    }

    public function login()
    {
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));

        $result = $this->user->login($email, $password);
        $data = $this->session->userdata('userdata');

        if ($result) $this->welcome($data);
        else        $this->index();
    }

    public function thank()
    {
        $data['title'] = 'Thank';
        $this->load->view('header_view', $data);
        $this->load->view('registration_view.php', $data);
        $this->load->view('footer_view', $data);
    }

    public function registration()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $this->user->add_user();
            $this->thank();
        }
    }

    public function logout()
    {
        $check = $this->session->unset_userdata('userdata');
        $this->session->sess_destroy();
        if (empty($check)) {
            redirect('/welcome/index', 'refresh');
        }

    }

    public function submitPostLike($Data = '')
    {
        if (empty($Data)) {
            $Data = $_POST;
        }
        $sessiondata = $this->session->userdata('userdata');
        $user_id = $sessiondata['user_id'];
        $post_id = $Data['post_id'];
        $date_created = date("Y-m-d H:i:s");

        $result = $this->user->addPostLike($post_id, $user_id, $date_created);

        if ($result) {
            $this->user->updatepostlike($post_id);
            // mysql_query("UPDATE fb_post set total_like = total_like + 1 where post_id = '$post_id'");
            $Return = array();
            $Return['ResponseCode'] = 200;
            $Return['Message'] = "like successfully.";
        } else {
            $Return = array();
            $Return['ResponseCode'] = 511;
            $Return['Message'] = "Error : Please try again!";
        }

        echo json_encode($Return);
    }

    public function submitPostDisLike($Data = '')
    {
        if (empty($Data)) {
            $Data = $_POST;
        }
        $sessiondata = $this->session->userdata('userdata');
        $user_id = $sessiondata['user_id'];
        $post_id = $Data['post_id'];
        $result = $this->user->addPostdisLike($post_id, $user_id);
        //$result = mysql_query("DELETE from fb_post_likes where user_id = '$user_id' and post_id = '$post_id'");

        if ($result) {
            $this->user->updatepostdislike($post_id);
            //mysql_query("UPDATE fb_post set total_like = total_like - 1 where post_id = '$post_id'");
            $Return = array();
            $Return['ResponseCode'] = 200;
            $Return['Message'] = "dislike successfully.";
        } else {
            $Return = array();
            $Return['ResponseCode'] = 511;
            $Return['Message'] = "Error : Please try again!";
        }

        echo json_encode($Return);
    }

    public function submitPostComment($Data = '')
    {
        if (empty($Data)) {
            $Data = $_POST;
        }
        $sessiondata = $this->session->userdata('userdata');
        $user_id = $sessiondata['user_id'];
        $post_id = $Data['post_id'];
        $comment = $Data['comment'];
        $date_created = date("Y-m-d H:i:s");
        $result = $this->user->addcomment($post_id, $user_id, $comment, $date_created);
        //$result = mysql_query("INSERT into fb_comment(post_id,user_id,comment,comment_date) values('$post_id','$user_id','$comment','$date_created')");

        if ($result) {
            $Return = array();
            $Return['ResponseCode'] = 200;
            $Return['Message'] = "comment submitted successfully.";
        } else {
            $Return = array();
            $Return['ResponseCode'] = 511;
            $Return['Message'] = "Error : Please try again!";
        }

        echo json_encode($Return);
    }

    function submitWallPost($Data = '')
    {
        $file = $_FILES["image"];
        $target_dir = "uploads/";
        $target_file = $target_dir.basename($_FILES["image"]["name"]);
        if(move_uploaded_file($_FILES["image"]["tmp_name"],$target_file)){
            echo "ok";
        }else{
            echo "fail";
        }
        if (empty($Data)) {
            $Data = $_POST;
        }
        //print_r($Data);exit;
        $sessiondata = $this->session->userdata('userdata');
        $user_id = $sessiondata['user_id'];
        $content = $Data['post_feed'];
        $imagedata = $_FILES["image"]["name"];
        $date_created = date("Y-m-d H:i:s");
        $result = $this->user->addpost($user_id, $content, $date_created, $imagedata);

        if ($result) {
            $Return = array();
            $Return['ResponseCode'] = 200;
            $Return['Message'] = "post updated successfully.";
        } else {
            $Return = array();
            $Return['ResponseCode'] = 511;
            $Return['Message'] = "Error : Please try again!";
        }
        echo json_encode($Return);


    }
    function uploadfile()
    {
        $file = $_FILES["image"];
        $target_dir = "uploads/";
        $target_file = $target_dir.basename($_FILES["image"]["name"]);
        if(move_uploaded_file($_FILES["image"]["tmp_name"],$target_file)){
            echo "ok";
        }else{
            echo "fail";
        }
    }

    function getUserDetails($user_id)
    {
        $user_query = mysql_query("select * from fb_users where user_id = '" . $user_id . "'");
        $userInfo = mysql_fetch_assoc($user_query);

        return $userInfo;
    }

    public function getWallPost()
    {
        $sessiondata = $this->session->userdata('userdata');

        $user_id = $sessiondata['user_id'];
        //$wall_query = mysql_query("SELECT p.*,u.name as username, pl.like_id from fb_post p left join fb_users u on u.user_id = p.user_id left join fb_post_likes pl on pl.post_id = p.post_id and pl.user_id = '$user_id' group by p.post_id order by post_id desc");
        $query = $this->user->wallquery($user_id);
        $row = $query->result();
        $postInfo = $row;
        return $postInfo;
    }

    public function getallcommentsforposts()
    {
        $query = $this->user->getallcommentsquery();
        $row = $query->result();
        $commentInfo = $row;

        return $commentInfo;
    }
public function deletecomment(){
    if($_POST['action'] == 'delete'){
    $this->user->deletecomment($_POST['id']);
    }
}
    public function getPostComments($post_id)
    {
        //$comment_query = mysql_query("SELECT c.*,u.name as username from fb_comment c left join fb_users u on u.user_id = c.user_id where c.post_id = '$post_id' order by c.comment_id desc");
        $query = $this->user->getcommentsquery($post_id);
        $row = $query->result();
        $commentInfo = $row;

        return $commentInfo;
    }

    public function personalpage($user_id = '')
    {
        $postdatam = $this->getpersonalWallPost($user_id);
        $data['comments'] = $this->getallcommentsforposts();
        $data['postdata'] = $postdatam;
        $data['title'] = 'Welcome';
        $this->load->view('header_view', $data);
        $this->load->view('personal-page.php', $data);
        $this->load->view('footer_view', $data);
    }

    public function getpersonalWallPost($user_id)
    {
        $sessiondata = $this->session->userdata('userdata');
        //$wall_query = mysql_query("SELECT p.*,u.name as username, pl.like_id from fb_post p left join fb_users u on u.user_id = p.user_id left join fb_post_likes pl on pl.post_id = p.post_id and pl.user_id = '$user_id' group by p.post_id order by post_id desc");
        $query = $this->user->personalwallquery($user_id);
        $row = $query->result();
        $postInfo = $row;
        return $postInfo;
    }

    public function invitefriends()
    {
        if($_POST['action']=='email'){
        $Dataa = $_POST;
        $sessiondata = $this->session->userdata('userdata');
        $user_id = $sessiondata['user_id'];
        $email = $Dataa['post_email'];
echo $email;exit;
        $date_created = date("Y-m-d H:i:s");
        $result = $this->user->invitefriendemail($user_id, $email, $date_created);

        if ($result) {
            $Return = array();
            $Return['ResponseCode'] = 200;
            $Return['Message'] = "post updated successfully.";
        } else {
            $Return = array();
            $Return['ResponseCode'] = 511;
            $Return['Message'] = "Error : Please try again!";
        }
        echo json_encode($Return);
        }
    }
}