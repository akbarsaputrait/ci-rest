<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'controllers/Rest.php';
class Api extends Rest
{

    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
        $this->cektoken();
    }

    /* index page */
    public function task_get($token = '')
    {
        header('Content-Type: application/json'); 
        if ($token == '') {
            // baseurl/?table=nama_table (semua data)
            $this->db->select('user_id, task, status');
            $data = $this->db->get('task')->result();
            echo json_encode(array('data' => $data, 'success' => true));
        } else {
            // baseurl/?table=nama_table&id=id (satu data)
            $this->db->where('token', $token);
            $this->db->select('user_id, task, status');
            $data = $this->db->get('task')->result();
            echo json_encode(array('data' => $data, 'success' => true));
        }
        //$this->response(array("data" => $data,'status'=>'success',), 200);
    }

    public function task_post()
    { // baseurl/?table=nama_table
        header('Content-Type: application/json'); 
        $insert = $this->db->insert('task', $this->post());
        $id = $this->db->insert_id();
        if ($insert) {
            $response = array(
                'data' => $this->post(),
                'table' => 'task',
                'id' => $id,
                'status' => 'success',
            );
            echo json_encode(array('data' => $response, 'success' => true));
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }

    public function task_put($token = '')
    { // baseurl/nama_table/id
        header('Content-Type: application/json'); 
        $this->db->where('token', $token);
        $update = $this->db->update('task', $this->put());
        if ($update) {
            $response = array(
                'data' => $this->put(),
                'table' => 'task',
                'id' => $token,
                'status' => 'success',
            );
            echo json_encode(array('data' => $response, 'success' => true));
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }

    public function task_delete($token = '')
    {
        header('Content-Type: application/json'); 
        $this->db->where($token, $token);
        $delete = $this->db->delete('task');
        if ($delete) {
            $response = array(
                'table' => 'task',
                'id' => $token,
                'status' => 'success',
            );
            echo json_encode(array('data' => $response, 'success' => true));
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }
}
