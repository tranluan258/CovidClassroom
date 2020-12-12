<?php
    class SubjectController extends BaseController{

        public function add_student(){
            if(isset($_POST['btn-add-student'])){
                $subject = new SubjectModel();

                $list_student = $subject->get_list_student_not_join($_SESSION['currentcode']);
                $subjectinfo = $subject->get_subject($_SESSION['currentcode']);

                $this->render('add_student.html',array('student'=>$list_student,'subjectinfo'=>$subjectinfo));
            }
        }


        public function add_student_choose(){
            if(isset($_POST['add-student-choose'])){
                $error = '';
                $subject = new SubjectModel();
                $data_email_teacher = $subject->get_email_by_username($_SESSION['username']);
                $email_teacher = $data_email_teacher['email'];
                $teacher_name = $data_email_teacher['name'];
                $code = $_SESSION['currentcode'];
                $message = "Bạn được mời tham gia vào lớp học, sử dụng code: <b>$code</b> để tham gia vào lớp.";
                if(isset($_POST['liststudent'])){
                    $list_choose = $_POST['liststudent'];

                    for($i=0;$i<count($list_choose);$i++){
                        $subject->send_email_student($email_teacher,$teacher_name,$list_choose[$i],$message);
                    }
                    $error = 'Mời học sinh vào lớp thành công';
                    $_SESSION['error'] = $error;
                    header("Location: detail");

                }

            }
        }

        public function add_subject(){
            if(isset($_POST['addnewclass'])) {
                $error = '';
                $classname = '';
                $subjectname = '';
                $room = '';
                $teacher = $_SESSION['username'];
                if (isset($_POST['classname']) && isset($_POST['subjectname']) && isset($_POST['room'])) {
                    $classname = $_POST['classname'];
                    $subjectname = $_POST['subjectname'];
                    $room = $_POST['room'];

                    if (empty($classname)) {
                        $error = 'Vui lòng nhập tên lớp học';
                    } else if (empty($subjectname)) {
                        $error = 'Vui lòng nhập tên môn học';
                    } else if (empty($room)) {
                        $error = 'Vui lòng nhập phòng học';
                    } else {
                        $subject = new SubjectModel();
                        $result = $subject->add_subject($teacher, $classname, $subjectname, $room);
                        if ($result['code'] == 1) {
                            $error = 'Tạo lớp học thất bại';
                        } else {
                            $error = 'Tạo lớp học thành công';
                        }
                    }

                    $_SESSION['error'] = $error;
                    header("Location: ../");
                }
            }
        }

        public function add_notice(){
            if(isset($_POST['addnotice'])){
                $error = '';
                $info = '';
                $username = $_SESSION['username'];
                $code = $_SESSION['currentcode'];

                if(isset($_POST['noticeinfo'])){
                    $noticeinfo = $_POST['noticeinfo'];
                    if(empty($noticeinfo)){
                        $error = 'Vui lòng nhập thông báo';
                    }else{
                        $subject = new SubjectModel();
                        $data_subject = $subject->get_subject($code);
                        $subject_class = $data_subject['data'][0]['classname'];
                        $subject_name = $data_subject['data'][0]['subjectname'];
                        $subject_room = $data_subject['data'][0]['room'];

                        $data_email_user_post = $subject->get_email_by_username($_SESSION['username']);
                        $email_user_post = $data_email_user_post['email'];
                        $user_post_name = $data_email_user_post['name'];

                        $message = "Lớp <b>$subject_class</b>, Môn <b>$subject_name</b>, Phòng <b>$subject_room</b>: <b>$user_post_name</b> đã đăng một thông báo";
                        $result = $subject->add_notice($code,$username,$noticeinfo);
                        if ($result['code'] == 1) {
                            $error = 'Đăng thông báo thất bại';
                        } else {
                            $error = 'Đăng thông báo thành công';
                        }
                        $listStudent = $subject->view_people_student($code);
                        foreach ($listStudent as &$lStudent){
                            foreach ($lStudent as &$value){
                                $student_user = $value['student'];
                                $data_email_student = $subject->get_email_by_username($student_user);
                                $email_student = $data_email_student['email'];

                                $subject->send_email_student($email_user_post,$user_post_name,$email_student,$message);
                            }
                        }
                    }
                    $_SESSION['error'] = $error;
                    header("Location: detail");
                }
            }
        }

        public function add_comment(){
            if(isset($_POST['addcomment'])){
                $error = '';
                $username = $_SESSION['username'];
                $idnotice = $_SESSION['currentnotice'];
                if(isset($_POST['noticecomment'])){
                    $comment = $_POST['noticecomment'];
                    if(empty($comment)){
                        $error = 'Vui lòng nhập bình luận';
                    }else{
                        $subject = new SubjectModel();
                        $result = $subject->add_comment($idnotice,$username,$comment);
                        if ($result['code'] == 1) {
                            $error = 'Bình luận thất bại';
                        } else {
                            $error = 'Bình luận thành công';
                        }
                    }
                    $_SESSION['error'] = $error;
                    header("Location: notice");
                }
            }
        }

        public function add_classwork(){
            if(isset($_POST['addclasswork'])){
                $error = '';
                $info = '';
                $username = $_SESSION['username'];
                $code = $_SESSION['currentcode'];
                if(isset($_POST['classworkinfo'])){
                    $noticeinfo = $_POST['classworkinfo'];
                    if(empty($noticeinfo)){
                        $error = 'Vui lòng nhập thông báo';
                    }else{
                        $subject = new SubjectModel();

                        $data_subject = $subject->get_subject($code);
                        $subject_class = $data_subject['data'][0]['classname'];
                        $subject_name = $data_subject['data'][0]['subjectname'];
                        $subject_room = $data_subject['data'][0]['room'];

                        $data_email_user_post = $subject->get_email_by_username($_SESSION['username']);
                        $email_user_post = $data_email_user_post['email'];
                        $user_post_name = $data_email_user_post['name'];

                        $message = "Lớp <b>$subject_class</b>, Môn <b>$subject_name</b>, Phòng <b>$subject_room</b>: <b>$user_post_name</b> đã đăng một bài tập";

                        $file_name = $_FILES['file']['name'];
                        $link = 'app/upload/'.$file_name;
                        $result = $subject->add_classwork($code,$username,$noticeinfo,$link);
                        move_uploaded_file($_FILES['file']['tmp_name'],$link);


                        if ($result['code'] == 1) {
                            $error = 'Đăng thông báo thất bại';
                        } else {
                            $error = 'Đăng thông báo thành công';
                        }

                        $listStudent = $subject->view_people_student($code);
                        foreach ($listStudent as &$lStudent){
                            foreach ($lStudent as &$value){
                                $student_user = $value['student'];
                                $data_email_student = $subject->get_email_by_username($student_user);
                                $email_student = $data_email_student['email'];

                                $subject->send_email_student($email_user_post,$user_post_name,$email_student,$message);
                            }
                        }


                    }
                    $_SESSION['error'] = $error;
                    header("Location: detail");
                }
            }
        }

        public function join_code(){
            $error = '';
            if(isset($_POST['join'])){
                $code = $_POST['join_code'];
                $subject = new SubjectModel();
                $data = $subject->join_code($code,$_SESSION['username']);
                if($data['code'] == 1){
                    $error = "Vào lớp thất bại! Vui lòng kiểm tra lại code.";
                } else {
                    $error = "Vào lớp thành công";
                }
                $_SESSION['error'] = $error;
                header("Location: ../");
            }
        }

        public function leave_class(){
            $error = '';
            $code = $_SESSION['currentcode'];
            $student = $_SESSION['username'];
            if(isset($_POST['leaveclass'])){
                $subject = new SubjectModel();
                $data = $subject->leave_class($code,$student);
                if($data['code'] == 1){
                    $error = "Rời lớp học thất bại!";
                } else {
                    $error = "Rời lớp học thành công!";
                }
                $_SESSION['error'] = $error;
                header("Location: ../");
            }
        }

        public function delete_student(){
            $error = '';
            $code = $_SESSION['currentcode'];
            if(isset($_POST['deletestudent'])){
                $student = $_POST['currentstudentdelete'];
                $subject = new SubjectModel();
                $data = $subject->leave_class($code,$student);
                if($data['code'] == 1){
                    $error = "Xóa học sinh khỏi lớp thất bại!";
                } else {
                    $error = "Xóa học sinh khỏi lớp thành công!";
                }
                $_SESSION['error'] = $error;
                header("Location: detail");
            }
        }

        public function delete_comment(){
            $error = '';
            $idnotice = $_SESSION['currentnotice'];
            if(isset($_POST['deletecomment'])){
                $idcmt = $_POST['currentcommentdelete'];
                $subject = new SubjectModel();
                $data = $subject->delete_comment($idcmt,$idnotice);
                if($data['code'] == 1){
                    $error = "Xóa bình luận thất bại!";
                } else {
                    $error = "Xóa bình luận thành công!";
                }
                $_SESSION['error'] = $error;
                header("Location: notice");
            }
        }


        public function update_notice_info(){
            $error = '';
            $idnotice = $_SESSION['currentnotice'];
            if(isset($_POST['btn_update_notice'])){
                if(isset($_POST['update_notice'])){
                    $info = $_POST['update_notice'];
                    $subject = new SubjectModel();
                    $data = $subject->update_notice($idnotice,$info);
                    if($data['code'] == 1){
                        $error = "Cập nhật thông báo thất bại";
                    } else {
                        $error = "Cập nhật thông báo thành công";
                    }
                    $_SESSION['error'] = $error;
                    header("Location: notice");
                }
            }
        }

        public function update_class_info(){
            $error = '';
            $code = $_SESSION['currentcode'];
            if(isset($_POST['btn_update_class'])){
                if(isset($_POST['update_class_name']) && isset($_POST['update_class_subject']) && isset($_POST['update_class_room'])){
                    $classname = $_POST['update_class_name'];
                    $subjectname = $_POST['update_class_subject'];
                    $room = $_POST['update_class_room'];
                    $subject = new SubjectModel();
                    $data = $subject->update_class($code,$classname,$subjectname,$room);
                    if($data['code'] == 1){
                        $error = "Cập nhật lớp học thất bại";
                    } else {
                        $error = "Cập nhật lớp học thành công";
                    }
                    $_SESSION['error'] = $error;
                    header("Location: detail");
                }
            }
        }

        public function detail()
        {
            $error = 'Xin chào' . ' ' . $_SESSION['yourname'] . '!';;

            $subject = new SubjectModel();
            if(isset($_POST['joinintoclass'])){
                unset($_SESSION['currentcode']);
            }
            if (isset($_SESSION['currentcode'])) {
                $notice = $subject->view_notice($_SESSION['currentcode']);
                $teacher = $subject->view_people_teacher($_SESSION['currentcode']);
                $student = $subject->view_people_student($_SESSION['currentcode']);
                $dataSubject = $subject->get_subject($_SESSION['currentcode']);
            }else{
                $notice = $subject->view_notice($_POST['currentcode']);
                $teacher = $subject->view_people_teacher($_POST['currentcode']);
                $student = $subject->view_people_student($_POST['currentcode']);
                $dataSubject = $subject->get_subject($_POST['currentcode']);
                $_SESSION['currentcode'] = $_POST['currentcode'];
            }
            $datacmt = array();
            foreach ($notice as &$noticecmt){
                foreach ($noticecmt as &$value){
                    $currentnotice = $value['idnotice'];
                    $cmt = $subject->view_comment_highlight_notice($value['idnotice']);
                    array_push($datacmt,array($currentnotice=>$cmt));
                }
            }


            if (isset($_SESSION['error'])) {
                $error = $_SESSION['error'];
                unset($_SESSION['error']);
            }
            $data = array("errordetail" => $error, 'type' => $_SESSION['type'], 'notice' => $notice, 'noticecmt'=>$datacmt, 'teacher'=>$teacher, 'student'=>$student, 'subjectinfo'=>$dataSubject);

            $this->render('detail.html', $data);
        }

        public function update_class(){
            $subject = new SubjectModel();

            $dataSubject = $subject->get_subject($_SESSION['currentcode']);
            $data = array('type' => $_SESSION['type'],'username' => $_SESSION['username'], 'subject' => $dataSubject);
            $this->render('update_class.html',$data);
        }

        public function notice(){
            $subject = new SubjectModel();
            if(isset($_POST['viewnotice'])){
                unset($_SESSION['currentnotice']);
            }
            if (isset($_SESSION['currentnotice'])) {
                $result = $subject->view_comment_notice($_SESSION['currentnotice']);
                $infonotice = $subject->get_notice_by_id($_SESSION['currentnotice']);
            }else{
                $result = $subject->view_comment_notice($_POST['currentnotice']);
                $infonotice = $subject->get_notice_by_id($_POST['currentnotice']);
                $_SESSION['currentnotice'] = $_POST['currentnotice'];
            }
            $data = array('type' => $_SESSION['type'],'username' => $_SESSION['username'], 'comment' => $result, 'notice' => $infonotice);

            $this->render('notice.html', $data);
        }

        public function update_notice(){
            $subject = new SubjectModel();

            $infonotice = $subject->get_notice_by_id($_SESSION['currentnotice']);

            $data = array('type' => $_SESSION['type'],'username' => $_SESSION['username'], 'notice' => $infonotice);
            $this->render('update_notice.html',$data);
        }

        public function delete_notice(){
            $error = '';
            $idnotice = $_SESSION['currentnotice'];
            if(isset($_POST['delete_notice'])){
                $subject = new SubjectModel();
                $data = $subject->delete_notice($idnotice);
                if($data['code'] == 1){
                    $error = "Xóa thông báo thất bại!";
                } else {
                    $error = "Xóa thông báo thành công!";
                }
                $_SESSION['error'] = $error;
                header("Location: detail");
            }
        }

        public function delete_subject(){
            $error = '';
            $code = $_SESSION['currentcode'];
            if(isset($_POST['delete_subject'])){
                $subject = new SubjectModel();
                $all_notice = $subject->get_all_notice($code);
                foreach ($all_notice as &$notice){
                    foreach ($notice as &$id){
                        $delete_notice = $subject->delete_notice($id['idnotice']);
                        if($delete_notice['code'] == 1){
                            $error = "Xảy ra lỗi khi xóa lớp";
                        }else{
                            $error = "Xóa lớp thành công";
                        }
                    }
                }
                $delete_class = $subject->delete_subject($code);
                if($delete_class['code'] == 1){
                    $error = "Xảy ra lỗi khi xóa lớp";
                }else{
                    $error = "Xóa lớp thành công";
                }
                $_SESSION['error'] = $error;
                header("Location: ../");
            }
        }

    }
?>