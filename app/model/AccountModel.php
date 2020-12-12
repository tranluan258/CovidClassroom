<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    class AccountModel extends BaseModel{


        function get_all()
        {
            $sql = 'select * from account';
            $data = $this->query_account($sql);

            return array('data'=>$data['data']);
        }

        function get_by_id($id)
        {

        }

        public function get_account($username){
            $sql = "select * from account where not username = '$username'";
            $data = $this->query_account($sql);

            return array('data'=>$data['data']);
        }

        public function login($username,$pass){
            $sql = 'select * from account where username = ?';
            $params = array('s',&$username);

            $data = $this->query_one_select($sql,$params);
            if($data['data'] == null){
                return null;
            }else{
                $hash_password = $data['data']['password'];

                if(!password_verify($pass,$hash_password)){
                    return null;
                }
                else{
                    return $data;
                }
            }
        }

        public function is_username_exist($username){
            $sql = "select username from account where username = ?";
            $params = array('s',&$username);
            $data = $this->query_one_select($sql,$params);

            return $data['data'];
        }

        public function signup($username,$password,$yourname,$birthday,$email,$phone){
            if($this->is_username_exist($username) != null){
                return array('code'=>1, 'message'=>"Username đã tồn tại");
            }
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $rand = random_int(0,1000);
            $token = md5($username .'+'. $rand);

            $sql = "insert into account(username,password,yourname,birthday,email,phone,active_token) values (?,?,?,?,?,?,?)";

            $params = array('sssssss',&$username,&$hash,&$yourname,&$birthday,&$email,&$phone,&$token);

            $data = $this->query_prepare_insert($sql,$params);
            if($data['code'] == 1){
                return array('code'=>2, 'message'=>'Đăng kí thất bại');
            }else{
                $this->sendVerificationEmail($email,$token);
                return array('code'=>0, 'message'=>'Đăng kí thành công');
            }
        }

        public function sendVerificationEmail($email,$token){
            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
                $mail->Username   = 'covidclassroom212@gmail.com';                     // SMTP username
                $mail->Password   = 'pkjuqroxharcgqpp';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->CharSet  = 'UTF-8';
                $mail->setFrom('covidclassroom212@gmail.com', 'Admin');
                $mail->addAddress($email, 'Người nhận');     // Add a recipient
                // $mail->addAddress('ellen@example.com');               // Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');

                // // Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Xác minh tài khoản của bạn';
                $mail->Body    = "Click vào <a href='http://localhost/account/actived?email=$email&token=$token'>Vào đây</a>để xác minh tài khoản";
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function activatedToken($email,$token){
            $sql = 'select username from account where email = ?  and active_token  = ? and actived = 0';
            $param = array('ss',&$email,&$token);
            $data = $this->query_one_select($sql,$param);

            if($data['code'] == 1){
                return array('code'=>1,"message"=>'Xảy ra lỗi');
            }

            if($data['data'] == null){
                return array('code'=>2,'message'=>'Email not found');
            }

            $sql = "update account set actived = 1 ,active_token= '' where email = ?";

            $param = array('s',&$email);

            $data = $this->query_prepare_update($sql,$param);

            if($data['code'] == 1){
                return array('code'=>1,"message"=>'Xảy ra lỗi');
            }else{
                return array('code'=>0,'message'=>'Thành công');
            }
        }

        public function forgot($email){ 
            $sql = 'select username from account where email = ?';
            $params = array('s',&$email);
            $data = $this->query_one_select($sql,$params);
            if($data['code'] == 1){
                return array('code'=>1,"message"=>'Xảy ra lỗi');
            }

            if($data['data'] == null){
                return array('code'=>2,'message'=>'Email not found');
            }

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $code = '';
            for ($i = 0; $i < 7; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }
            $hash = password_hash($code,PASSWORD_DEFAULT);
            $sql = "update account set password = '$hash' where email = ?";
            $param = array('s',&$email);

            $data = $this->query_prepare_update($sql,$param);

            if($data['code'] == 1){
                return array('code'=>1,"message"=>'Xảy ra lỗi');
            }else{
                $this->sendEmailPass($email,$code);
                return array('code'=>0,'message'=>'Thành công');
            }
        }

        public function sendEmailPass($email,$code){
            $mail = new PHPMailer(true);

            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
                $mail->Username   = 'covidclassroom212@gmail.com';                     // SMTP username
                $mail->Password   = 'pkjuqroxharcgqpp';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('covidclassroom212@gmail.com', 'Admin');
                $mail->addAddress($email, 'Người nhận');     // Add a recipient
                // $mail->addAddress('ellen@example.com');               // Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');

                // // Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Forgot Password';
                $mail->Body    = "Đây là mật khẩu mới: $code của bạn vui lòng đăng nhập để đổi mật khẩu";
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function profile($username){
            $sql = 'select * from account where username = ?';
            $param = array('s',&$username);

            $data = $this->query_one_select($sql,$param);

            return $data['data'];
        }

        public function update_type($username,$type){
            $sql = 'select type from account where username = ?';
            $param = array('s',&$username);
            $user = $this->query_one_select($sql,$param);
            $sql = "update account set type = $type where username = ?";
            $param = array('s',&$username);
            $data = $this->query_prepare_update($sql,$param);

            return $data['code'];
        }

        public function update_pass($username,$pass){
            $hash = password_hash($pass,PASSWORD_DEFAULT);
            $sql = "update account set password = '$hash' where username = ?";
            $param = array('s',&$username);
            $data = $this->query_prepare_update($sql,$param);

            return $data['code'];
        }
    }
?>