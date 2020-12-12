<?php
    class HomeController extends BaseController{
        public function error(){
            $data = array('Title'=>'Error','message'=>'');

            $this->render('error.html',$data);
        }

        public function index()
        {
            $error = 'Xin chào'. ' '. $_SESSION['yourname'] . '!';
            if(!isset($_SESSION['username'])){
                header("Location: account/login");
                exit();
            }else{
                $home = new HomeModel();
                if($_SESSION['type'] != 2){
                    $result = $home->view_subject($_SESSION['username'],$_SESSION['type']);
                }else{
                    $result = $home->get_all();
                }
                if(isset($_SESSION['error'])){
                    $error =$_SESSION['error'];
                    unset($_SESSION['error']);
                }
                $data = array('Title'=>'Trang chủ','name'=>$_SESSION['username'],'yourname'=>$_SESSION['yourname'], 'type'=>$_SESSION['type'],'subject'=>$result,"errorindex"=>$error);

                $this->render('index.html',$data);
            }
        }

        public function search(){
            $q = $_REQUEST["q"];
            $q = strtolower($q);
            $home = new HomeModel();
            if($_SESSION['type'] != 2){
                $result = $home->view_subject($_SESSION['username'],$_SESSION['type']);
            }else{
                $result = $home->get_all();
            }
            $array = array();
            foreach ($result['data'] as $key){
                if(stristr($q, substr($key['subjectname'], 0, strlen($q))) || stristr($q, substr($key['room'], 0, strlen($q))) || stristr($q, substr($key['classname'], 0, strlen($q)))){
                    array_push($array,$key);
                }
            }
            echo json_encode($array);
        }

    }
?>