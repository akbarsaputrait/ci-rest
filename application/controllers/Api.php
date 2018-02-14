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
    public function task_get($id = '')
    {
        header('Content-Type: application/json'); 
        $get_id = 'user_id';
        if ($id == '') {
            // baseurl/?table=nama_table (semua data)
            $data = $this->db->get('task')->result();
        } else {
            // baseurl/?table=nama_table&id=id (satu data)
            $this->db->where($get_id, $id);
            $data = $this->db->get('task')->result();
        }
        echo json_encode(array('data' => $data, 'success' => true));
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

    public function task_put($id = '')
    { // baseurl/nama_table/id
        header('Content-Type: application/json'); 
        $get_id = 'user_id';
        $this->db->where($get_id, $id);
        $update = $this->db->update('task', $this->put());
        if ($update) {
            $response = array(
                'data' => $this->put(),
                'table' => 'task',
                'id' => $id,
                'status' => 'success',
            );
            echo json_encode(array('data' => $response, 'success' => true));
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }

    public function task_delete($id = '')
    {
        header('Content-Type: application/json'); 
        $get_id = 'user_id';
        $this->db->where($get_id, $id);
        $delete = $this->db->delete('task');
        if ($delete) {
            $response = array(
                'table' => 'task',
                'id' => $id,
                'status' => 'success',
            );
            echo json_encode(array('data' => $response, 'success' => true));
        } else {
            echo json_encode(array('status' => 'fail', 502));
        }
    }
}
