<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {


    // agar tidak masuk keadmin smbarangan
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->model('Role_model');
    }

    public function index(){

        $data['title'] = 'Dashboard';
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header' , $data);
        $this->load->view('templates/sidebar' , $data);
        $this->load->view('templates/topbar' , $data);
        $this->load->view('admin/index' , $data);
        $this->load->view('templates/footer');

         
    }

    public function role(){
        $data['title'] = 'Role';
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false){
            $this->load->view('templates/header' , $data);
            $this->load->view('templates/sidebar' , $data);
            $this->load->view('templates/topbar' , $data);
            $this->load->view('admin/role' , $data);
            $this->load->view('templates/footer');

        }else {
            // mengingklusi pesan berhasil
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('flash', ' Added');
              redirect('admin/role');
        }

    }

    public function hapusRole($id)
    {
     
        $this->load->model('Role_model', 'role');
        $this->role->hapusDataRole($id);
        $this->session->set_flashdata('flash', ' Delete');
        redirect('admin/role');
    
    }



    public function roleAccess($role_id){
        $data['title'] = 'Role Access'; 
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] =$this->db->get('user_menu')->result_array();
 
        $this->load->view('templates/header' , $data);
        $this->load->view('templates/sidebar' , $data);
        $this->load->view('templates/topbar' , $data);
        $this->load->view('admin/role-access' , $data);
        $this->load->view('templates/footer');   

    }

    public function changeAccess(){
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if($result->num_rows() < 1){
            $this->db->insert('user_access_menu', $data);
        }else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('flash', ' Access Changed');    
    }

    public function report()
    {
        $data['title'] = 'Data User';
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        
        $data['report'] = $this->db->get('user_report')->result_array();
        
        $this->form_validation->set_rules('report', 'Report', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header' , $data);
            $this->load->view('templates/sidebar' , $data);
            $this->load->view('templates/topbar' , $data);
            $this->load->view('admin/report' , $data);
            $this->load->view('templates/footer');
            
        }else {
            // mengingklusi pesan berhasil
            $this->db->insert('user_report', ['report' => $this->input->post('report')]);
            $this->session->set_flashdata('flash', ' Added');
              redirect('admin/report');
        }
       
    }

    public function hapus($id)
    {

        $this->load->model('Role_model', 'role');
        $this->role->hapusDataReport($id);
        $this->session->set_flashdata('flash', ' Delete');
        redirect('admin/report');
    }

    public function pdf(){
        $this->load->library('dompdf_gen');

        $data['report'] = $this->Role_model->tampildata('user_menu')->result();

        $this->load->view('admin/laporan_pdf', $data);
        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("laporan_report.pdf", array('Attachment' => 0));
    }

    public function excel(){
        $data['report'] = $this->Role_model->tampildata('user_menu')->result();

        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();

        $object->getProperties()->setCreator("Framework Indonesia");
        $object->getProperties()->setLastModifiedBy("Framework Indonesia");
        $object->getProperties()->setTitle("Daftar Menu User");

        $object->setActiveSheetIndex(0);

        $object->getActiveSheet()->setCellValue('A1', 'Name');
        $object->getActiveSheet()->setCellValue('B1', 'Email');
        $object->getActiveSheet()->setCellValue('C1', 'Jurusan');
      
        $baris = 2;
        $i = 1;

        foreach($data['report'] as $re) {
            $object->getActiveSheet()->setCellValue('A1', $baris, $i++);
            $object->getActiveSheet()->setCellValue('B1', $baris, $re->name);
            $object->getActiveSheet()->setCellValue('C1', $baris, $re->email);
            $object->getActiveSheet()->setCellValue('D1', $baris, $re->jurusan);
          

            $baris++;
        }
       
        $filename = "Data_Menu Report".'xlsx';

        $object->getActiveSheet()->setTitle("Data_Menu__User");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer=PHPExcel_IOFactory::createwriter($object, 'Excel2007');
        $writer->save('php://output');

        exit;
    }

    
}