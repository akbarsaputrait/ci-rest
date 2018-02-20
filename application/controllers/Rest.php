<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '../vendor/autoload.php';
require APPPATH . '/libraries/REST_Controller.php';
use \Firebase\JWT\JWT;

class Rest extends REST_Controller
{
    private $secretkey = 'ZrmjJpOLm9';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function login_post()
    {
        header('Content-Type: application/json');
        $this->load->model('loginmodel');
        $username = $this->post('username', true);
        $pass = $this->post('password', true);
        $data = $this->loginmodel->is_valid($username);

        if ($data) {
            if (password_verify($this->post('password'), password_hash($data->password, PASSWORD_DEFAULT))) {
                $payload['id'] = $data->id;
                $payload['username'] = $data->username;
                $payload['email'] = $data->email;
                $payload['date'] = date('d-m-Y H:i:s');
                $output['token'] = JWT::encode($payload, $this->secretkey);
                // return $this->response($output,REST_Controller::HTTP_OK);
                echo json_encode(array('data' => $payload, 'token' => $output, 'success' => true));

                // $this->db
                //     ->set('token', $output['token'])
                //     ->where('token', $data->token)
                //     ->update('client');
                // $this->db
                //     ->set('token', $output['token'])
                //     ->where('token', $data->token)
                //     ->update('task');
                // $this->db
                //     ->insert('history_logs', array('username' => $payload['username'], 'pc' => $payload['pc'], 'date' => $payload['date'], 'status' => 'Login', 'token' => $output['token']));
            } else {
                echo json_encode(array('error' => true, 'message' => 'Token not generated'));
            }
        } else {
            echo json_encode(array('error' => true, 'message' => 'User not registered'));
        }
    }

    public function check_post()
    {
        header('Content-Type: application/json');
        $header = $this->input->get_request_header('Authorization', true);
        if ($decode = JWT::decode($header, $this->secretkey, array('HS256'))) {
            $data = $this->db->query('SELECT * FROM client WHERE id = "' . $decode->id . '" LIMIT 1 ');
            if ($data) {
                $client = array();
                foreach ($data->result() as $row) {
                    $client['id'] = $row->id;
                    $client['username'] = $row->username;
                    $client['email'] = $row->email;
                    $client['pc'] = $row->pc;
                }
                // echo json_encode($client);
                return $client;
            }
            return false;
        }
        return false;
    }
}
