<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    class SubjectModel extends BaseModel{


        function get_all()
        {
            // TODO: Implement get_all() method.
        }

        function get_by_id($id)
        {
            // TODO: Implement get_by_id() method.
        }


        public function add_subject($teacher,$classname,$subjectname,$room){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $sql = "insert into subject(code,teacher,classname,subjectname,room) values (?,?,?,?,?)";
            $params = array('sssss',&$code,&$teacher,&$classname,&$subjectname,&$room);

            $data = $this->query_prepare_insert($sql,$params);
            if($data['code']==1){
                return array('code'=>1, 'message'=>'Tạo lớp học thất bại');
            }else{
                return array('code'=>0, 'message'=>'Tạo lớp học thành công');
            }
        }

        public function join_code($code,$student){
            $sql = "SELECT code from subject where code = ?";
            $params = array('s',&$code);
            $data = $this->query_prepare_select($sql,$params);
            if($data['code'] == 1){
                $error = 'Sai code';
            }else{
                $sql = "insert into subject_info(code,student) values(?,?)";
                $params = array('ss',&$code,&$student);
                $data = $this->query_prepare_insert($sql,$params);
                if($data['code'] == 1){
                    return array('code'=>1,'error'=>'That bai');
                }else{
                    return array('code'=>0,'error'=>'Thanh cong');
                }
            }
        }

        public function view_notice($code){
            $sql = "select notice.id, notice.username, notice.information,notice.link, notice.datepost, account.yourname, account.type from notice,account where notice.username = account.username and class = ?";
            $params = array('s',&$code);
            $dataSubject = $this->query_prepare_select($sql,$params);
            if($dataSubject['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();

                while ($item = $dataSubject['data']->fetch_assoc()){
                    array_push($data,array('idnotice'=>$item['id'],'username'=>$item['username'],'information'=>$item['information'],'link'=>$item['link'],'datepost'=>$item['datepost'],'name'=>$item['yourname'],'type'=>$item['type']));
                }
                return array('data'=>$data);
            }
        }

        public function view_comment_highlight_notice($idnotice){
            $sql = "select notice_comment.id, notice_comment.username, notice_comment.comment, account.yourname, account.type from notice_comment, account where notice_comment.username = account.username and idnotice = ? ORDER BY notice_comment.id";
            $params = array('i',&$idnotice);
            $dataNotice = $this->query_prepare_select($sql,$params);
            if($dataNotice['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                $count = 0;
                while ($item = $dataNotice['data']->fetch_assoc()){
                    if($count<3){
                        array_push($data,array('idcmt'=>$item['id'],'username'=>$item['username'],'comment'=>$item['comment'],'name'=>$item['yourname'],'type'=>$item['type']));
                        $count++;
                    }

                }
                return array('data'=>$data);
            }
        }


        public function view_comment_notice($idnotice){
            $sql = "select notice_comment.id, notice_comment.username, notice_comment.comment, account.yourname, account.type from notice_comment, account where notice_comment.username = account.username and idnotice = ? ORDER BY notice_comment.id";
            $params = array('i',&$idnotice);
            $dataNotice = $this->query_prepare_select($sql,$params);
            if($dataNotice['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataNotice['data']->fetch_assoc()){
                    array_push($data,array('idcmt'=>$item['id'],'username'=>$item['username'],'comment'=>$item['comment'],'name'=>$item['yourname'],'type'=>$item['type']));
                }
                return array('data'=>$data);
            }
        }

        public function view_people_teacher($code){
            $sql = "select subject.teacher, account.yourname, account.email from subject,account where subject.teacher = account.username and code = ?";
            $params = array('s',&$code);
            $dataTeacher = $this->query_prepare_select($sql,$params);
            if($dataTeacher['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataTeacher['data']->fetch_assoc()){
                    array_push($data,array('teacher'=>$item['teacher'],'name'=>$item['yourname'],'email'=>$item['email']));
                }
                return array('data'=>$data);
            }
        }

        public function view_people_student($code){
            $sql = "select subject_info.student, account.yourname, account.email from subject_info,account where subject_info.student = account.username and code = ?";
            $params = array('s',&$code);
            $dataStudent = $this->query_prepare_select($sql,$params);
            if($dataStudent['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataStudent['data']->fetch_assoc()){
                    array_push($data,array('student'=>$item['student'],'name'=>$item['yourname'],'email'=>$item['email']));
                }
                return array('data'=>$data);
            }
        }


        public function get_subject($code){
            $sql = "select classname, subjectname, room from subject where code = ?";
            $params = array('s',&$code);
            $dataSubject = $this->query_prepare_select($sql,$params);
            if($dataSubject['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataSubject['data']->fetch_assoc()){
                    array_push($data,array('classname'=>$item['classname'],'subjectname'=>$item['subjectname'],'room'=>$item['room']));
                }
                return array('data'=>$data);
            }
        }

        public function get_notice_by_id($idnotice){
            $sql = "select notice.username, notice.information,notice.link, notice.datepost, account.yourname, account.type from notice,account where account.username = notice.username and id = ?";
            $params = array('i',&$idnotice);
            $dataNotice = $this->query_prepare_select($sql,$params);
            if($dataNotice['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataNotice['data']->fetch_assoc()){
                    array_push($data,array('username'=>$item['username'],'info'=>$item['information'],'link'=>$item['link'],'datepost'=>$item['datepost'],'name'=>$item['yourname'],'type'=>$item['type']));
                }
                return array('data'=>$data);
            }
        }

        public function get_email_by_username($username){
            $sql = "select email, yourname from account where username = ?";
            $params = array('s',&$username);
            $email = $this->query_one_select($sql,$params);

            return array('email'=>$email['data']['email'],'name'=>$email['data']['yourname']);
        }

        public function get_list_student_not_join($code){
            $sql =  "SELECT account.username,account.yourname,account.email FROM account WHERE account.type = 0 and not EXISTS(SELECT subject_info.student FROM subject_info WHERE subject_info.student = account.username and code = ?)";
            $params = array('s',&$code);
            $list_data = $this->query_prepare_select($sql,$params);
            if($list_data['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $list_data['data']->fetch_assoc()){
                    array_push($data,array('username'=>$item['username'],'name'=>$item['yourname'],'email'=>$item['email']));
                }
                return array('data'=>$data);
            }

        }

        public function add_notice($code,$username,$information){
            $sql = "insert into notice(class,username,information) values (?,?,?)";
            $params = array('sss',&$code,&$username,&$information);

            $data = $this->query_prepare_insert($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'Đăng thông báo thất bại!');
            }else{
                return array('code'=>0, 'message'=>'Đăng thông báo thành công!');
            }
        }

        public function add_comment($idnotice,$username,$comment){
            $sql = "insert into notice_comment(idnotice,username,comment) values (?,?,?)";
            $params = array('sss',&$idnotice,&$username,&$comment);

            $data = $this->query_prepare_insert($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'Bình luận thất bại!');
            }else{
                return array('code'=>0, 'message'=>'Bình luận thành công!');
            }
        }

        public function add_classwork($code,$username,$information,$link){
            $sql = "insert into notice(class,username,information,link) values (?,?,?,?)";
            $params = array('ssss',&$code,&$username,&$information,&$link);

            $data = $this->query_prepare_insert($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'Đăng thông báo thất bại!');
            }else{
                return array('code'=>0, 'message'=>'Đăng thông báo thành công!');
            }
        }

        public function leave_class($code,$student){
            $sql = "DELETE FROM `subject_info` WHERE code = ? and student = ?";
            $params = array('ss',&$code,&$student);

            $data = $this->query_prepare_delete($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'Rời lớp học thất bại!');
            }else{
                return array('code'=>0, 'message'=>'Rời lớp học thành công!');
            }
        }

        public function delete_comment($idcmt,$idnotice){
            $sql = "DELETE FROM `notice_comment` WHERE id = ? and idnotice = ?";
            $params = array('ss',&$idcmt,&$idnotice);

            $data = $this->query_prepare_delete($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'Xóa bình luận thất bại!');
            }else{
                return array('code'=>0, 'message'=>'Xóa bình luận thành công!');
            }
        }

        public function delete_notice($idnotice){
            $sql = "DELETE FROM `notice_comment` WHERE idnotice = ?";
            $params = array('i',&$idnotice);
            $data = $this->query_prepare_delete($sql,$params);
            $sql1 = "DELETE FROM `notice` WHERE id = ?";
            $data1 = $this->query_prepare_delete($sql1,$params);

            if($data['code']==0 and $data1['code']==0){
                return array('code'=>0, 'message'=>'thanh cong');
            }else{
                return array('code'=>1, 'message'=>'that bai');
            }
        }

        public function get_all_notice($code){
            $sql = "select id from notice where class = ?";
            $params = array('s',&$code);
            $dataSubject = $this->query_prepare_select($sql,$params);
            if($dataSubject['code'] == 1){
                $error = 'Lỗi';
            }else{
                $data = array();
                while ($item = $dataSubject['data']->fetch_assoc()){
                    array_push($data,array('idnotice'=>$item['id']));
                }
                return array('data'=>$data);
            }
        }

        public function delete_subject($code){
            $params = array('s',&$code);

            $sql1 = "DELETE FROM `subject_info` WHERE code = ?";
            $data1 = $this->query_prepare_delete($sql1,$params);

            $sql = "DELETE FROM `subject` WHERE code = ?";
            $data = $this->query_prepare_delete($sql,$params);

            if($data['code']== 0 and $data1['code'] == 0){
                return array('code'=>0, 'message'=>'Xóa lớp thành công!');
            }else{
                return array('code'=>1, 'message'=>'Xóa lớp thất bại!');
            }
        }

        public function update_notice($idnotice,$info){
            $sql = "UPDATE `notice` SET `information`= ? WHERE id = ?";
            $params = array('si',&$info,&$idnotice);

            $data = $this->query_prepare_update($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'thất bại!');
            }else{
                return array('code'=>0, 'message'=>'thành công!');
            }
        }

        public function update_class($code,$classname,$subjectname,$room){
            $sql = "UPDATE `subject` SET `classname`= ? ,`subjectname`= ?,`room`= ? WHERE code = ?";
            $params = array('ssss',&$classname,&$subjectname,&$room,&$code);

            $data = $this->query_prepare_update($sql,$params);

            if($data['code']==1){
                return array('code'=>1, 'message'=>'thất bại!');
            }else{
                return array('code'=>0, 'message'=>'thành công!');
            }
        }


        public function send_email_student($email_teacher,$teacher_name,$email_student,$message){
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
                $mail->setFrom($email_teacher, $teacher_name);
                $mail->addAddress($email_student, 'Sinh viên');     // Add a recipient
                // $mail->addAddress('ellen@example.com');               // Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');

                // // Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Thông báo';
                $mail->Body    = $message;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                echo $e;
            }
        }
    }
?>