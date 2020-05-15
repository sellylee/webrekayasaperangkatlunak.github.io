<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model{
    public function getSubMenu()
    {

        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
        FROM `user_sub_menu` JOIN `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        ";
       return $this->db->query($query)->result_array();
    }

    // public function getMenuById($id) 
    // {
    //     return $this->db->get_where('menu', ['id' => $id])->row_array();
    // }

    public function hapusDataMenu($id)
          {
           $this->db->delete('user_menu', ['id' => $id]);
          }

     public function MenuEdit()
         {
          $data = [
              "menu" => $this->input->post('menu' , true)
            ];

                  $this->db->where('id', $this->input->post('id'));
                  $this->db->update('user_menu', $data);
                }

    public function hapusDatasubMenu($id)
          {
           $this->db->delete('user_sub_menu', ['id' => $id]);
          }

    public function MenusubEdit()
          {
           $data = [
               "menu" => $this->input->post('menu' , 'submenu', true)
             ];
 
                   $this->db->where('id', $this->input->post('id'));
                   $this->db->update('user_menu', $data);
                 }

      public function tampildata(){
      return $this->db->get('user_sub_menu');
              }

    
}
 