<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: wenja
 * Date: 2017/11/19
 * Time: 13:39
 */
require 'ResponseJSON.php';

class Staff extends CI_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index() {

//        $this->load->view('dist/index.html');
    }

    public function validate() {
        // post data: {userName, userpwd}
        $post_user = json_decode(file_get_contents("php://input"));
        $result = new ResponseJSON();
        // check post user: name, pwd
        if ($post_user->userName == null || $post_user->userpwd == null) {
            $result->info = 'user error';
            echo json_encode($result);
            return;
        }
        $checkResult = $this->mysql_model->checkUserPwd($post_user);
        if ($checkResult != null) {
            $result->status = true;
            $result->info = json_encode($checkResult);
        } else {
            $result->status = false;
            $result->info = 'validate error';
        }
        echo json_encode($result);

    }

//    public function checkUserPwd($user) {
//        $db_user = $this->mysql_model->get_row(STAFF,'(userName="'.$user->userName.'") or (mobile="'.$user->userName.'") ');
//        if (count($db_user) > 0 && $db_user['userpwd'] == $user->userpwd) {
//            // validate user success
//            return $db_user;
//        } else {
//            return null;
//        }
//    }
}