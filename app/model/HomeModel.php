<?php
    class HomeModel extends BaseModel{

        function get_all()
        {
            $sql = 'SELECT subject.code, subject.teacher,subject.subjectname,subject.room, subject.classname, account.yourname FROM subject, account WHERE account.username = subject.teacher';

            $data = $this->query_subject($sql);
            return $data;

        }

        function get_by_id($id)
        {
            // TODO: Implement get_by_id() method.
        }

        public  function  view_subject($username,$type){
            if($type == 1){
                $sql = 'SELECT subject.code, subject.teacher, subject.subjectname, subject.room, subject.classname, account.yourname FROM subject, account WHERE account.username = subject.teacher and subject.teacher = ?';
            }else if($type == 0) {
                $sql = 'SELECT subject_info.code, subject.teacher, subject.subjectname, subject.room, subject.classname, account.yourname FROM subject_info,subject, account WHERE account.username = subject.teacher and subject_info.code = subject.code and subject_info.student = ?';
            }
            $param = array('s',&$username);

            $data = $this->query_more_select_subject($sql,$param);

            return $data;

        }

    }

?>