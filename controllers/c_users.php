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

    public function editProfile() {
                    # don't let other users get to profile...
                    if(!$this->user) {
                            die("Members only. <a href='/users/login'>Login</a>");
                    }
            
                    #Set up the view
                $this->template->content = View::instance('v_users_editProfile');
                $this->template->title = "Edit Profile";
                
                # Prepare the data array to be inserted
                $data = Array(
                            "first_name" => $this->user->first_name,
                 "last_name" => $this->user->last_name,
                 "email" => $this->user->email,
                 "password" => $this->user->password,
                 "user_id" => $this->user->user_id
                 );
                
                #Pass the data to the View
                $this->template->content->user = $data;
                
                #Display the view
                echo $this->template;
            }

            public function p_editProfile($id) {
                # this user, not others...and must be logged in, too!
                if(!$this->user) {
                        die("Members only. <a href='/users/login'>Login</a>");
        }
        
                # Set up the View
                $this->template->content = View::instance('v_users_p_editProfile');
                
                # Prevent SQL injection attacks by sanitizing the data the user entered in the form
                $_POST = DB::instance(DB_NAME)->sanitize($_POST);
            
                $q = 'SELECT password
                        FROM users
                        WHERE user_id = '.$id;
                    
                $current_password = DB::instance(DB_NAME)->query($q);
            
                # for future use... if I want to allow password to pre-populate or be empty
                if ($_POST['password'] != ''){
                    # Encrypt the password (with salt)
                        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);
                        $_POST['confirm_password'] = sha1(PASSWORD_SALT.$_POST['password']);
            }
            
                unset($_POST['confirm_password']);
            
            # set error & same vars to false
        $error = false;
        $same = false;
                
        # initiate error
        $this->template->content->error = '<br>';
        
                                                                                        
        #query for matches on this new email address
        $search_emails = "SELECT user_id FROM users WHERE email = '". $_POST['email']."'";
        #execute the query
        $count_q = DB::instance(DB_NAME)->query($search_emails);
        #get the number of rows where that email exists
        $email_matches = mysqli_num_rows($count_q);
        
                # if we have a match, is it this user?
                if ($email_matches > 0) {
                        #get the user_id
                        $email_user_id = DB::instance(DB_NAME)->select_row($search_emails);
                        #print_r($email_user_id['user_id']);
                        #print_r($this->user->user_id);

                        # if the user_id is a match,         
                        if( $email_user_id['user_id'] == $this->user->user_id) {

                                        #do nothing
                        }        
                        # set the error flag.
                        else {
                                $error = true;
                        }
                }
                
                # at some point I need to error check when changing the email address.
                # Maybe in version 2...
                if ($error == true) {
                        $this->template->content = View::instance('v_users_p_editProfile');
                        $this->template->content->error = 'This email address is already in use by another account.';

                        echo $this->template;
                }
            
                elseif(!$error) {
                        # Set the modified time
                        $_POST['modified'] = Time::now();
                        # be sure to Associate this post with this user
                        $_POST['user_id'] = $this->user->user_id;
         
                        $where_condition = 'WHERE user_id = '.$id;
     
                         $updated_post = DB::instance(DB_NAME)->update('users', $_POST, $where_condition);
                         
                        # Send them back to the login page.
                        Router::redirect("/users/profile");
                }
                else {
                        echo $this->template;
                }
        }

    public function signup() {
        # Setup view
            $this->template->content = View::instance('v_users_signup');
            $this->template->title   = "Sign Up";

        # Render template
            echo $this->template;

    }

public function photo() {
        # Setup view
            $this->template->content = View::instance('v_users_photo');
            $this->template->title   = "Photo";

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
                      
        # Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        # Hash submitted password so we can compare it against one in the db
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        # Search the db for this email and password
        # Retrieve the token if it's available
        $q = "SELECT token 
            FROM users 
            WHERE email = '".$_POST['email']."' 
            AND password = '".$_POST['password']."'";

        $token = DB::instance(DB_NAME)->select_field($q);

        # If we didn't find a matching token in the database, it means login failed
        if(!$token) {
                        Router::redirect('/users/login_fail');  
                    }
        
        # But if we did, login succeeded!
                else {
                        /* 
                    Store this token in a cookie using setcookie()
                    Important Note: *Nothing* else can echo to the page before setcookie is called
                    Not even one single white space.
                    param 1 = name of the cookie
                    param 2 = the value of the cookie
                    param 3 = when to expire
                    param 4 = the path of the cooke (a single forward slash sets it for the entire domain)
                    */
                    setcookie("token", $token, strtotime('+1 year'), '/');

                    # Send them to the main page - or whever you want them to go
                    Router::redirect("/users/profile");
                }
           
    }

    public function login_fail() {
        
                $this->template->content = View::instance('v_login_fail');            
                echo $this->template;                 
           
        }

    

    
        public function logout() {
            #Send user back to the login page
            Router::redirect('/users/login');
        }

        public function profile() {
                
                # If user is blank, they're not logged in; redirect them to the login page
                if(!$this->user) {
                    Router::redirect('/users/login');
                }

                # If they weren't redirected away, continue:

                # Setup view
                $this->template->content = View::instance('v_users_profile');
                $this->template->title   = "Profile of".$this->user->first_name;

                # Render template
                echo $this->template;
                            
                }

    


} # end of the class
