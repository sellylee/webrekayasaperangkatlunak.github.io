<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    // agar tidak masuk keadmin smbarangan
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->model('Menu_model');
        // $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Menu Management';
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();

        
        $data['menu'] = $this->db->get('user_menu')->result_array();
        
        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header' , $data);
            $this->load->view('templates/sidebar' , $data);
            $this->load->view('templates/topbar' , $data);
            $this->load->view('menu/index' , $data);
            $this->load->view('templates/footer');
        }else {
            // mengingklusi pesan berhasil
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('flash', ' Added');
              redirect('menu');
        }
    }

        public function hapus($id)
        {
            $this->Menu_model->hapusDataMenu($id);
            $this->session->set_flashdata('flash', ' Delete');
            redirect('menu');
        
        }


        public function edit($id){

            $data['title'] = 'Form Edit'; 
            $data['menu'] = $this->Menu_model->MenuEdit($id);
           

            $this->form_validation->set_rules('menu', 'Menu', 'required');


            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header' , $data);
                $this->load->view('menu/edit', $data);
                $this->load->view('templates/footer');
            }else {
                // mengingklusi pesan berhasil
                $this->Menu_model->MenuEdit();
                $this->session->set_flashdata('flash', ' Edited');
                  redirect('menu');
            }

        }



    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        
        $data['user'] = $this->db->get_where('user', ['email' =>
        $this->session->userdata('email')])->row_array();
        
        $this->load->model('Menu_model', 'menu');

        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array(); 

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'icon', 'required');
      

        if ($this->form_validation->run() == false)
        {
            $this->load->view('templates/header' , $data);
            $this->load->view('templates/sidebar' , $data);
            $this->load->view('templates/topbar' , $data);
            $this->load->view('menu/submenu' , $data);
            $this->load->view('templates/footer');
        } else {
           $data = [
               'title' => $this->input->post('title'),
               'menu_id' => $this->input->post('menu_id'),
               'url' => $this->input->post('url'),
               'icon' => $this->input->post('icon'),
               'is_active' => $this->input->post('is_active')
           ];
          
           $this->db->insert('user_sub_menu', $data);
           $this->session->set_flashdata('flash', ' Added');
           redirect('menu/submenu');

           
        }
    }


    public function hapusub($id)
    {
        $this->Menu_model->hapusDatasubMenu($id);
        $this->session->set_flashdata('flash', ' Delete');
        redirect('menu/submenu');
    
    }


    public function editsub($id){

        $data['title'] = 'Form Edit'; 
        $data['menu'] = $this->Menu_model->MenusubEdit($id);
       

        // $data['menu'] = $this->db->get('user_menu')->result_array(); 
        
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu', 'Menu', 'subMenu', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'icon', 'required');


        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header' , $data);
            $this->load->view('menu/editsub', $data);
            $this->load->view('templates/footer');
        }else {
            // mengingklusi pesan berhasil
            $this->Menu_model->MenusubEdit();
            $this->session->set_flashdata('flash', ' Edited');
              redirect('menu/submenu');
        }

    }

    public function pdf(){
        $this->load->library('dompdf_gen');

        $data['subMenu'] = $this->Menu_model->tampildata('user_menu')->result();

        $this->load->view('menu/laporan_pdf', $data);
        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $this->dompdf->set_paper($paper_size, $orientation);

        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("laporan_menu.pdf", array('Attachment' => 0));
    }



    public function excel(){
        $data['subMenu'] = $this->Menu_model->tampildata('user_menu')->result();

        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();

        $object->getProperties()->setCreator("Framework Indonesia");
        $object->getProperties()->setLastModifiedBy("Framework Indonesia");
        $object->getProperties()->setTitle("Daftar Menu");

        $object->setActiveSheetIndex(0);

        $object->getActiveSheet()->setCellValue('A1', 'Title');
        $object->getActiveSheet()->setCellValue('B1', 'Menu');
        $object->getActiveSheet()->setCellValue('C1', 'Url');
        $object->getActiveSheet()->setCellValue('D1', 'Icon');
        $object->getActiveSheet()->setCellValue('E1', 'Active');

        $baris = 2;
        $i = 1;

        foreach($data['subMenu'] as $sM) {
            $object->getActiveSheet()->setCellValue('A1', $baris, $i++);
            $object->getActiveSheet()->setCellValue('B1', $baris, $sM->title);
            $object->getActiveSheet()->setCellValue('C1', $baris, $sM->menu_id);
            $object->getActiveSheet()->setCellValue('D1', $baris, $sM->url);
            $object->getActiveSheet()->setCellValue('E1', $baris, $sM->icon);
            $object->getActiveSheet()->setCellValue('E1', $baris, $sM->is_active);

            $baris++;
        }
       
        $filename = "Data_Menu".'xlsx';

        $object->getActiveSheet()->setTitle("Data_Menu");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer=PHPExcel_IOFactory::createwriter($object, 'Excel2007');
        $writer->save('php://output');

        exit;
    }



   
}