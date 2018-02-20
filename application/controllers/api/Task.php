<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'controllers/Rest.php';
class Task extends Rest
{
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->client = $this->check_post();
    }

    public function index_get()
    {
        header('Content-Type: application/json');
        // if ($userid == '') { 
        //     $this->db->select('userid, task, status');
        //     $data = $this->db->get('task')->result();
        //     echo json_encode(array('data' => $data, 'success' => true));
        // } else 
        // if{
            $this->db->where('userid', $this->client['id']);
            $this->db->select('task, status');
            $data = $this->db->get('task')->result();
            echo json_encode(array('data' => $data, 'success' => true));
        // }
    }

    public function index_post()
    {
        header('Content-Type: application/json');
        $insert = $this->db->insert('task', $this->post());
        if ($insert) {
            $response = array(
                'data' => $this->post(),
                'success' => true
            );
            echo json_encode($response);
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }

    // public function task_put($userid = '')
    // {
    //     header('Content-Type: application/json');
    //     $this->db->where('userid', $this->client['id']);
    //     $update = $this->db->update('task', $this->put());
    //     if ($update) {
    //         $response = array(
    //             'data' => $this->put(),
    //             'table' => 'task',
    //             'token' => $token,
    //             'success' => true,
    //         );
    //         echo json_encode($response);
    //     } else {
    //         echo json_encode(array('error' => true, 502));
    //     }
    // }

    // public function task_delete($userid = '')
    // {
    //     header('Content-Type: application/json');
    //     $this->db->where('', $token);
    //     $delete = $this->db->delete('task');
    //     if ($delete) {
    //         $response = array(
    //             'table' => 'task',
    //             'id' => $token,
    //             'status' => 'success',
    //         );
    //         echo json_encode(array('data' => $response, 'success' => true));
    //     } else {
    //         echo json_encode(array('error' => true, 502));
    //     }
    // }
}
