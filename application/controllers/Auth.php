<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
        $data['title'] = 'Login Page';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/auth_footer');
       } else {
        //    ktika validasiny success
        $this->_login();
       }
    }

    private function _login(){
         $email = $this->input->post('email');
         $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
       
        // jika user ny ada
        if ($user) {

        //   maka user nya aktip
            if ($user['is_active'] == 1) {

            //    cek password
            if (password_verify($password, $user['password'])) {
                $data = [
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);
                if($user['role_id'] == 1 ){
                    redirect('admin');
                }else{
                    redirect('user');
                }
              
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong
                password </div>');
                  redirect('auth');
            }


            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> this
                Email is not been activated
                </div>');
                  redirect('auth');
            }

        }else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
           Email is not Registered!
           </div>');
             redirect('auth');
        }
    }
    
    public function registration()
    {

        if ($this->session->userdata('email')) {
            redirect('user');
        }
        
        // nama
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        // email
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already registered' 
        ]);
        // password 1
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'password dont match!',
            'min_length' => 'Password too short!'
        ]);
        // password 2
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'WPU User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
           
        }else{ 
            $email = $this->input->post('email', true);
           $data = [
               'name' => htmlspecialchars($this->input->post('name', true)),
               'email' => htmlspecialchars($email),
               'image' => 'default.jpg',
               'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
               'role_id' => 2,
               'is_active' => 0,
               'date_created' =>time()
           ];

            //siapkan token  
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email'  => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);
            $this->db->insert('user_token  ', $user_token);


           //kirim ke email
           $this->_sendEmail($token, 'verify');


           $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
          Congratulations!! U account has been created. please active u account
         </div>');
           redirect('auth');
    }
 }

        private function _sendEmail($token, $type){
            $config = [
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_user' => 'rplsyafiiakrom@gmail.com',
                'smtp_pass' => 'rplaja:v',
                'smtp_port' => 465,
                'mailtype' => 'html',
                'chartset' => 'utf-8',
                'newline' => "\r\n"
            ];

            // $this->load->library('email', $config);

            
          $this->email->initialize($config);

          $this->email->from('rplsyafiiakrom@gmail.com', 'Rekayasa Perangkat Lunak');
          $this->email->to($this->input->post('email'));

         if ($type == 'verify') {
                $this->email->subject('Account Verification');
                $this->email->message('click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' .  $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');

      } else if ($type == 'forgot') {
        $this->email->subject('Reset Password');
        $this->email->message('click this link to reset u password : <a href="' . base_url() . 'auth/resetpassword?email=' .  $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
     }

          if ($this->email->send()) {
                return true;

          }else {
              echo $this->email->print_debugger();
              die;
          }
        }
        



        public function verify()
        {
            $email = $this->input->get('email');
            $token = $this->input->get('token');
    
            $user = $this->db->get_where('user', ['email' => $email])->row_array();
    
            if ($user) {
                $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
    
                if ($user_token) {
                    if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                        //jika berhasil
                        $this->db->set('is_active', 1);
                        $this->db->where('email', $email);
                        $this->db->update('user');
    
                        $this->db->delete('user_token', ['email' => $email]);
    
    
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $email . ' has been actived! please login
                            
                            </div>');
                        redirect('auth');
                    } else {

                       // jika token kadaluarsa
                        $this->db->delete('user', ['email' => $email]);
                        $this->db->delete('user_token', ['email' => $email]);
    
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                        Account activation failed! your token expired
                        </div>');
                        redirect('auth');
                    }
    
    
                   
                } else {
                     //jika token gagal
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                        Account activation failed! your token
                        </div>');
                    redirect('auth');
                }
                //jika email gagal
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                   Account activation failed! your email
                   </div>');
                redirect('auth');
            }
        }


          public function logout(){
              $this->session->unset_userdata('email');
              $this->session->unset_userdata('role_id');

              $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
              you have been logged out
             </div>');
             redirect('auth');
          }

        //   block masuk ke admin
          public function blocked(){
              $this->load->view('auth/blocked');
          }


          public function forgotpassword()
          {
              $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

              if ($this->form_validation->run() == false) {
                  $data['title'] = 'Forgot Password';
                  $this->load->view('templates/auth_header', $data);
                  $this->load->view('auth/forgot-password');
                  $this->load->view('templates/auth_footer');
              } else {
                  // kalo berhasil
                  $email = $this->input->post('email');
                  $user = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();
      
                  if ($user) {
                      $token = base64_encode(random_bytes(32));
                      $user_token = [
                          'email' => $email,
                          'token' => $token,
                          'date_created' => time()
                      ];
      
                      $this->db->insert('user_token', $user_token);
                      $this->_sendEmail($token, 'forgot');
      
                      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    please chek your email to reset password
                     </div>');
                      redirect('auth/forgotpassword');
                  } else {
                      $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                     email is not register or activated
                     </div>');
                      redirect('auth/forgotpassword');
                  }
              }
          }


          public function resetPassword()
          {
              $email = $this->input->get('email');
              $token = $this->input->get('token');
      
              $user = $this->db->get_where('user', ['email'])->row_Array();
              
              if($user) {
                  $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
      
                  if ($user_token) {
                      $this->session->set_userdata('reset_email', $email);
                      $this->changePassword();
                  } else {
                      //token tidak sesuai
                      $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                      reset password failes wrong token
                      </div>');
                      redirect('auth');
                  }
              } else {
                  //pesan eror email tdk sesuai password
                  $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                  reset password failes wrong email
                  </div>');
                  redirect('auth');
              }
          }

          public function changePassword()
          {
              if (!$this->session->userdata('reset_email')) {
                  redirect('auth');
              }
      
              //password1
              $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[3]|matches[password2]');
      
              //password 2
              $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[3]|matches[password1]');
              if ($this->form_validation->run() == false) {
                  $data['title'] = 'Change Password';
                  $this->load->view('templates/auth_header', $data);
                  $this->load->view('auth/change-password');
                  $this->load->view('templates/auth_footer');
              } else {
                  $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
                  $email = $this->session->userdata('reset_email');
      
                  $this->db->set('password', $password);
                  $this->db->where('email', $email);
                  $this->db->update('user');
      
                  $this->session->unset_userdata('reset_email');
      
                  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                  password has been change , please login
                  </div>');
                  redirect('auth');
              }
          }

         

}