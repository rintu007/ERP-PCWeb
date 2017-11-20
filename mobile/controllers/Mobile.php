<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by IntelliJ IDEA.
 * User: wenjin
 * Date: 2017/11/20
 * Time: 16:07
 */

class Mobile extends CI_Controller
{
    public function __construct(){
        parent::__construct();
    }
    public function index() {
        $this->load->view('index.html');
    }
}