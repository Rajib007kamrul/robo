<?php

class Robo_Registration {

    private $registration_errors = [];
    private $messages            = [];
    private static $instance;
    public $atts                 = [];
    public $userrole             = '';

    public function __construct() {
        add_action( 'init', [ $this, 'process_registration' ] );
        add_action( 'init', [ $this, 'process_teacher_registration' ] );
        add_action( 'init', [ $this, 'process_lost_password' ] );
        add_action( 'init', [ $this, 'process_reset_password' ] );

        // shortcode
        add_shortcode( 'robo_register_form', [ $this, 'robo_register_form' ] );
        add_shortcode( 'robo_reset_form', [ $this, 'robo_reset_form' ] );
        add_shortcode( 'robo_lostpass_form', [ $this, 'robo_lostpass_form' ] );

        //extra
        add_action( 'login_form_register', [ $this, 'redirect_to_custom_register' ] );
        add_action( 'login_form_lostpassword', [ $this, 'redirect_to_custom_lostpassword' ] );
        add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );

        // add_filter( 'register_url', [ $this, 'change_my_register_url' ]);
        // add_filter( 'lostpassword_url', [ $this, 'change_my_lostpassword_url' ]);
    }

    public function change_my_lostpassword_url( $lostpassword_url ) {
        if( is_admin() ) {
            return $lostpassword_url;
        }

       return home_url( 'password-lost' );
    }

    public function change_my_register_url( $url ) {
        if( is_admin() ) {

        error_log('hha');
            return $url;
        }
        // return $url;
       return home_url( 'registration' );
    }

    public static function init() {
        if ( !self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function robo_register_form( $atts, $content = "" ) {
        extract( shortcode_atts( [], $atts ) );
        ob_start();
        if( is_user_logged_in() ) {
            echo '<div class="alert alert-light" role="alert"> <h3 class="text-center"> your are already login </h3> </div>';
        } elseif ( ! get_option( 'users_can_register' ) ) {
            echo '<div class="alert alert-warning" role="alert"> _Registering new users is currently not allowed </div>';
        } else {

            Robo_Registration::init()->show_errors();
            Robo_Registration::init()->show_messages();
            get_template_part( 'template-parts/register-form' );
        }
        return ob_get_clean();
    }

    public function robo_reset_form( $atts, $content = "" ) {
        extract( shortcode_atts( [], $atts ) );
        ob_start();
        if ( is_user_logged_in() ) {
             echo '<div class="alert alert-light" role="alert"> <h3 class="text-center"> your are already login </h3> </div>';
        } else {
            Robo_Registration::init()->show_errors();
            Robo_Registration::init()->show_messages();
            get_template_part( 'template-parts/reset-pass-form' );
        }
        return ob_get_clean();
    }

    public function robo_lostpass_form( $atts, $content = "" ) {
        extract( shortcode_atts( [], $atts ) );
        ob_start();
        if( is_user_logged_in() ) {
             echo '<div class="alert alert-light" role="alert"> <h3 class="text-center"> your are already login </h3> </div>';
        } else {
            Robo_Registration::init()->show_errors();
            Robo_Registration::init()->show_messages();
            get_template_part( 'template-parts/lostpassword-form' );
        }
        return ob_get_clean();
    }

    public  function process_registration() {

        if ( !empty( $_POST['registersubmit'] ) && !empty( $_POST['robo_register_nonce'] ) ) {
            $userdata            = [];
            $userfirstname       = isset( $_POST['userfirstname'] ) ? $_POST['userfirstname']: '';
            $userlastname        = isset( $_POST['userlastname'] ) ? $_POST['userlastname'] : '';
            $useremail           = isset( $_POST['useremail'] ) ? $_POST['useremail'] : '';
            $username            = isset( $_POST['username'] ) ? $_POST['username']: '';
            $userpassword        = isset( $_POST['userpassword'] ) ? $_POST['userpassword']: '';
            $userconfirmpassword = isset( $_POST['userconfirmpassword'] ) ? $_POST['userconfirmpassword']: '';

            if ( empty( $userfirstname ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'First name is required.', 'robo' );
                return;
            }

            if ( empty( $userlastname ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Last name is required.', 'robo' );
                return;
            }

            if ( empty( $useremail ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Email is required.', 'robo' );
                return;
            }

            if ( empty( $username ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Username is required.', 'robo' );
                return;
            }

            if ( empty( $userpassword ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Password is required.', 'robo' );
                return;
            }

            if ( empty( $userconfirmpassword ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Confirm Password is required.', 'robo' );
                return;
            }

            if ( $userpassword != $userconfirmpassword ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Passwords are not same.', 'robo' );
                return;
            }

            if ( get_user_by( 'login', $username ) === $username ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'A user with same username already exists.', 'robo' );
                return;
            }

            $userdata['first_name'] = $userfirstname;
            $userdata['last_name']  = $userlastname;
            $userdata['user_email'] = $useremail;
            $userdata['user_pass']  = $userpassword;
            $userdata['user_login'] = $username;
            $userdata['role']       = 'student';

            $user_id = wp_insert_user( $userdata );

            if ( is_wp_error( $user_id ) ) {
                $this->registration_errors[] = $user_id->get_error_message();
                return;
            } else {

                if ( isset( $_POST['user_age'] ) ) {
                    update_user_meta( $user_id, 'user_age', $_POST['user_age'] );
                }

                if ( isset( $_POST['user_gender'] ) ) {
                    update_user_meta( $user_id, 'user_gender', $_POST['user_gender'] );
                }

                if ( isset( $_POST['user_school'] ) ) {
                    update_user_meta( $user_id, 'user_school', $_POST['user_school'] );
                }

                if ( isset( $_POST['user_parents_first'] ) ) {
                    update_user_meta( $user_id, 'user_parents_first', $_POST['user_parents_first'] );
                }

                if ( isset( $_POST['user_parents_last'] ) ) {
                    update_user_meta( $user_id, 'user_parents_last', $_POST['user_parents_last'] );
                }

                if ( isset( $_POST['user_phone'] ) ) {
                    update_user_meta( $user_id, 'user_phone', $_POST['user_phone'] );
                }

                if ( isset( $_POST['user_experience'] ) ) {
                    update_user_meta( $user_id, 'user_experience', $_POST['user_experience'] );
                }

                $blogname   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
                $this->messages[] = sprintf( __( 'Hi, %s', 'robo' ), $username ) . "\r\n";
                $this->messages[] = 'Congrats! You are Successfully registered to ' . $blogname . "\r\n\r\n";
            }
        }
    }

    public function process_teacher_registration() {
        error_log(print_r($_POST,true));
        if ( !empty( $_POST['registerteachersubmit'] ) && !empty( $_POST['robo_register_nonce'] ) ) {
            $userdata            = [];
            $registration_errors = [];
            $userfirstname       = isset( $_POST['userfirstname'] ) ? $_POST['userfirstname']: '';
            $userlastname        = isset( $_POST['userlastname'] ) ? $_POST['userlastname'] : '';
            $useremail           = isset( $_POST['useremail'] ) ? $_POST['useremail'] : '';
            $username            = isset( $_POST['username'] ) ? $_POST['username']: '';
            $userpassword        = isset( $_POST['userpassword'] ) ? $_POST['userpassword']: '';
            $userconfirmpassword = isset( $_POST['userconfirmpassword'] ) ? $_POST['userconfirmpassword']: '';

            if ( empty( $userfirstname ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'First name is required.', 'robo' );
                return;
            }

            if ( empty( $userlastname ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Last name is required.', 'robo' );
                return;
            }

            if ( empty( $useremail ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Email is required.', 'robo' );
                return;
            }

            if ( empty( $username ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Username is required.', 'robo' );
                return;
            }

            if ( empty( $userpassword ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Password is required.', 'robo' );
                return;
            }

            if ( empty( $userconfirmpassword ) ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Confirm Password is required.', 'robo' );
                return;
            }

            if ( $userpassword != $userconfirmpassword ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'Passwords are not same.', 'robo' );
                return;
            }

            if ( get_user_by( 'login', $username ) === $username ) {
                $this->registration_errors[] = '<strong>' . __( 'Error', 'robo' ) . ':</strong> ' . __( 'A user with same username already exists.', 'robo' );
                return;
            }


            $userdata['first_name'] = $userfirstname;
            $userdata['last_name']  = $userlastname;
            $userdata['user_email'] = $useremail;
            $userdata['user_pass']  = $userpassword;
            $userdata['user_login'] = $username;
            $userdata['role']       = 'teacher';

            $user_id = wp_insert_user( $userdata );

            if ( is_wp_error( $user_id ) ) {
                $this->registration_errors[] = $user_id->get_error_message();
                return;
            } else {
                if ( isset( $_POST['user_school'] ) ) {
                    update_user_meta( $user_id, 'user_school', $_POST['user_school'] );
                }

                if( isset( $_POST['user_role'] ) ) {
                    update_user_meta( $user_id, '_user_category', $_POST['user_role'] );
                }

                if( isset( $_POST['usertitle'] ) ) {
                    update_user_meta( $user_id, 'usertitle', $_POST['usertitle'] );
                }
            }
        }
    }

    public function show_errors() {
        if ( $this->registration_errors ) {
            foreach ( $this->registration_errors as $error ) {
                echo '<div class="alert alert-danger" role="alert"><h3 class="text-center"> ' . __( $error, 'robo' ) . '</h3></div>';
            }
        }
    }

    public function show_messages() {
        if ( $this->messages ) {
            foreach ( $this->messages as $message ) {
                printf( '<div class="alert alert-success" role="alert"> <h3 class="text-center"> %s </h3> </div>', $message );
            }
        }
    }

    public static function get_posted_value( $key ) {
        if ( isset( $_REQUEST[$key] ) ) {
            $required_key = $_REQUEST[$key];
           return $required_key;
        }

        return '';
    }

    public function process_reset_password() {
        if ( isset( $_POST['pass1'] ) && isset( $_POST['pass2'] ) && isset( $_POST['key'] ) && isset( $_POST['login'] ) && isset( $_POST['robo_reset_nonce'] ) ) {

            $pass1 = sanitize_text_field( wp_unslash( $_POST['pass1'] ) );
            $pass2 = sanitize_text_field( wp_unslash( $_POST['pass2'] ) );
            $key = sanitize_text_field( wp_unslash( $_POST['key'] ) );
            $login = sanitize_text_field( wp_unslash( $_POST['login'] ) );
            $nonce = sanitize_text_field( wp_unslash( $_POST['robo_reset_nonce'] ) );

            $user = check_password_reset_key( $key, $login );

            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    $this->registration_errors[] = __( 'Expired Key.', 'robo' );
                } else {
                    $this->registration_errors[] = __( 'Invalid Key.', 'robo' );
                }
                exit;
            } else {
                // save these values into the form again in case of errors
                $args['key']   = $key;
                $args['login'] = $login;

                if ( empty( $pass1 ) || empty( $pass2 ) ) {
                    $this->registration_errors[] = __( 'Please enter your password.', 'robo' );
                    return;
                }

                if ( $pass1 !== $pass2 ) {
                    $this->registration_errors[] = __( 'Passwords do not match.', 'robo' );
                    return;
                }

                $this->reset_password( $user, $pass1 );
                $this->messages[] = 'password reset' . '<a href="'. wp_login_url() .'" alt="login"> Login </a>';
            }
        }
    }

    public function process_lost_password() {
        global $wpdb, $wp_hasher;

        if ( isset( $_POST['user_login'] ) && empty( $_POST['user_login'] ) && isset( $_POST['robo_lostpassword_nonce'] )  ) {
            $this->messages[] = __( 'Please fill the Username fields', 'robo' );
            return ;
        }

        if ( isset( $_POST['user_login'] ) && !empty( $_POST['user_login'] )  && isset( $_POST['robo_lostpassword_nonce'] )  ) {
            $username = $_POST['user_login'];
            if ( strpos( $username, '@' ) ) {
                $user_data = get_user_by( 'email', $username );
            } else {
                $user_data = get_user_by( 'login', $username );
            }

            if ( empty( $user_data ) ) {
                $this->messages[] = __( '<strong>ERROR</strong>: There is no user registered with that email address.', 'robo' );
                return ;
            }
            do_action( 'lostpassword_post' );

            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;
            $key        = get_password_reset_key( $user_data );

            if ( is_wp_error( $key ) ) {
                return $key;
            }

            // error_log(print_r($key,true));
            // Generate something random for a key...
           //  $key = wp_generate_password( 20, false );

           //  if ( empty( $wp_hasher ) ) {
           //      require_once ABSPATH . WPINC . '/class-phpass.php';
           //      $wp_hasher = new PasswordHash( 8, true );
           //  }

           //  // $key = time() . ':' . $wp_hasher->HashPassword( $key );
           //  $key = $wp_hasher->HashPassword( $key );

           // do_action( 'retrieve_password_key', $user_login, $user_email, $key );
           //  $wpdb->update( $wpdb->users, [ 'user_activation_key' => $key ], [ 'user_login' => $user_login ] );

            $message = __( 'Someone has requested a password reset for the following account:', 'robo' ) . "\r\n\r\n";
            $message .= network_home_url( '/' ) . "\r\n\r\n";
            $message .= sprintf( __( 'Username: %s', 'robo' ), $user_login ) . "\r\n\r\n";
            $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'robo' ) . "\r\n\r\n";
            $message .= __( 'To reset your password, visit the following address:', 'robo' ) . "\r\n\r\n";
            $message .= '<' . network_site_url( "wp-login.php?action=rp&key=".$key."&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
            $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

            $title = sprintf( __( '[%s] Password Reset', 'robo' ), $blogname );

            wp_mail( $user_email, wp_specialchars_decode( $title ), $message );

            $this->messages[] = __( 'Password has been reset. Please check your email.', 'robo' );
        }
    }


    public function redirect_to_custom_register() {
        if ( is_user_logged_in() ) {
            wp_redirect( home_url('/') );
        } else {
            wp_redirect( home_url( 'registration' ) );
        }
    }

    public function redirect_to_custom_lostpassword() {
        if ( is_user_logged_in() ) {
            wp_redirect( home_url('/') );
        } else {
            wp_redirect( home_url( 'password-lost' ) );
        }
    }

    public function redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            // Verify key / login combo
            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'login?login=invalidkey' ) );
                }
                exit;
            }

            $redirect_url = home_url( 'member-password-reset' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

            wp_redirect( $redirect_url );
            exit;
        }
    }

    // public function check_password_reset_key( $key, $login ) {
    //     global $wpdb;

    //     //keeping backward compatible
    //     if ( strlen( $key ) == 20 ) {
    //         $key = preg_replace( '/[^a-z0-9]/i', '', $key );
    //     }

    //     if ( empty( $key ) || !is_string( $key ) ) {
    //         $this->registration_errors[] = __( 'Invalid key', 'robo' );

    //         return false;
    //     }

    //     if ( empty( $login ) || !is_string( $login ) ) {
    //         $this->registration_errors[] = __( 'Invalid Login', 'robo' );

    //         return false;
    //     }

    //     $user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );

    //     if ( empty( $user ) ) {
    //         $this->registration_errors[] = __( 'Invalid key', 'robo' );

    //         return false;
    //     }

    //     return $user;
    // }

    public function reset_password( $user, $new_pass ) {
        do_action( 'password_reset', $user, $new_pass );

        wp_set_password( $new_pass, $user->ID );

        wp_password_change_notification( $user );
    }
}

Robo_Registration::init();
