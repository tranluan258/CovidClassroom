<?php
    class DetailController extends BaseController{
        public function get()
        {
            if($_GET['code']){
                echo $_GET['code'];
            }
        }
    }

?>