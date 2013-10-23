<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();;
    } 

    public function index() {
        echo "This is the index page";
    }

    public function addUser(){
        $data = Array(
            'first_name' => 'Sam', 
            'last_name' => 'Seaborn', 
            'email' => 'seaborn@whitehouse.gov');

        /*
        Insert requires 2 params
        1) The table to insert to
        2) An array of data to enter where key = field name and value = field data

        The insert method returns the id of the row that was created
        */
        $user_id = DB::instance(DB_NAME)->insert('users', $data);

        echo 'Inserted a new row; resulting id:'.$user_id;


    }

    public function signup() {
        # Setup view
            $this->template->content = View::instance('v_users_signup');
            $this->template->title   = "Sign Up";

        # Render template
            echo $this->template;

    }

    public function p_signup() {

            # More data we want stored with the user
            $_POST['created']  = Time::now();
            $_POST['modified'] = Time::now();

            # Encrypt the password  
            $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);                

            # Create an encrypted token via their email address and a random string
            $_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());


            # Insert this user into the database
            $user_id = DB::instance(DB_NAME)->insert('users', $_POST);

            # Confirm they signed up and show confirmation view
            $this->template->content = View::instance('v_users_signup_confirm');            
                echo $this->template; 
         
    }

    public function login() {
        
                $this->template->content = View::instance('v_users_login');            
                echo $this->template;   
           
        }
    
    public function p_login() {
                      
                $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
                
                //echo "<pre>";
                //print_r($_POST);
                //echo "</pre>";

                $q = 
                        'SELECT token 
                        FROM users
                        WHERE email = "'.$_POST['email'].'"
                        AND password = "'.$_POST['password'].'"';
                        
                        //echo $q;
           
                $token = DB::instance(DB_NAME)->select_field($q);
                
                # Success
                if($token) {
                        setcookie('token',$token, strtotime('+1 year'), '/');
                        $this->template->content = View::instance('v_users_profile');            
                        echo $this->template;   
                }
                # Fail
                else {
                        Router::redirect('/users/login_fail');
                }
           
    }

    public function login_fail() {
        
                $this->template->content = View::instance('v_login_fail');            
                echo $this->template;                 
           
        }

    

    
        public function logout() {
            echo "This is the logout page";
        }

        public function profile($user_name = NULL) {
                
                # Set up the View
                $this->template->content = View::instance('v_users_profile');
                $this->template->title = "Profile";
                
                # Load client files
                $client_files_head = Array(
                        '/css/profile.css',
                        );
                
                $this->template->client_files_head = Utils::load_client_files($client_files_head);
                
                $client_files_body = Array(
                        '/js/profile.js'
                        );
                
                $this->template->client_files_body = Utils::load_client_files($client_files_body);
                
                # Pass the data to the View
                $this->template->content->user_name = $user_name;
                
                # Display the view
                echo $this->template;
                        
                //$view = View::instance('v_users_profile');
                //$view->user_name = $user_name;                
                //echo $view;
                
    }

    


} # end of the class
