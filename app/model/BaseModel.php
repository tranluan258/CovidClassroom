<?php
    abstract class BaseModel{
        private $db;
        public function __construct(){
            $this->db = Database::open();
        }

        abstract function get_all();
        abstract function get_by_id($id);

        public function query_subject($sql){
            $result = $this->db->query($sql); //thieu db;
            if(!$result){
                return array('code'=>1,'error'=>$this->db->error);
            }
            $data = array();
            while($item = $result->fetch_assoc()){
                array_push($data,array('code'=>$item['code'],'teacher'=>$item['teacher'],'subjectname'=>$item['subjectname'],'room'=>$item['room'], 'classname'=>$item['classname'], 'teachername'=>$item['yourname']));
            }

            return array('data'=>$data);
        }

        public function query_account($sql){
            $result = $this->db->query($sql); //thieu db;
            if(!$result){
                return array('code'=>1,'error'=>$this->db->error);
            }
            $data = array();
            while($item = $result->fetch_assoc()){
                array_push($data,array('username'=>$item['username'],'yourname'=>$item['yourname'],'type'=>$item['type']));
            }

            return array('data'=>$data);
        }

        public function query_prepare_select($sql,$param){
            $stm = $this->db->prepare($sql);
            call_user_func_array(array($stm,'bind_param'),$param);

            if(!$stm->execute()){
                return array('code'=>1,'error'=>$this->db->error);
            }
            $result = $stm->get_result();
            return  array('code'=>0,'data'=>$result);
        }

        function query_one_select($sql,$param){
            $result = $this->query_prepare_select($sql,$param);
            if($result['code'] == 1){
                return array('code'=>1,'data'=>null);
            }else{
                $item = $result['data']->fetch_assoc();
                return array('code'=>0,'data'=>$item);
            }

        }

        function query_more_select_subject($sql,$param){
            $result = $this->query_prepare_select($sql,$param);

            $data = array();
            while ($item = $result['data']->fetch_assoc()){
                array_push($data,array('code'=>$item['code'],'teacher'=>$item['teacher'],'subjectname'=>$item['subjectname'],'room'=>$item['room'], 'classname'=>$item['classname'], 'teachername'=>$item['yourname']));
            }
            return array('data'=>$data);
        }

        public function query_prepare_insert($sql,$param){
            $stm = $this->db->prepare($sql);
            call_user_func_array(array($stm,'bind_param'),$param);

            if(!$stm->execute()){
                return array('code'=>1,'error'=>$this->db->error);
            }

            return array('code'=>0,'error'=>"Thành công");
        }

        public function query_prepare_update($sql,$param){
            $stm = $this->db->prepare($sql);
            call_user_func_array(array($stm,'bind_param'),$param);

            if(!$stm->execute()){
                return array('code'=>1,'error'=>$this->db->error);
            }

            return array('code'=>0,'error'=>"Thành công");
        }


        public function query_prepare_delete($sql,$param){
            $stm = $this->db->prepare($sql);
            call_user_func_array(array($stm,'bind_param'),$param);

            if(!$stm->execute()){
                return array('code'=>1,'error'=>$this->db->error);
            }

            return array('code'=>0,'error'=>"Thành công");
        }
    }

?>