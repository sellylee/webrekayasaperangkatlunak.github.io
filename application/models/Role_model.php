<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model{
    public function hapusDataRole($id)
              {
               $this->db->delete('user_role', ['id' => $id]);
              }

    public function hapusDataReport($id)
              {
               $this->db->delete('user_report', ['id' => $id]);
              }

    public function tampildata(){
        return $this->db->get('user_report');
        }
}