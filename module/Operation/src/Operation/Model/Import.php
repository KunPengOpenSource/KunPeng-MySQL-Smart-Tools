<?php
    namespace Operation\Model;
    
    class Import{
        protected $allowExt = 'sql';
        protected $maxSize = 1;
        protected $file = NULL;
        protected $errno = 0;
        protected $error = array(
            0 => '无错',
            1 => '上传文件超出系统限制',
            2 => '上传文件大小超出网页表单页面',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传',
            6 => '找不到临时文件',
            7 => '文件写入失败',
            8 => '不允许的文件后缀',
            9 => '文件大小超出的类的允许范围',
            10 => '创建目录失败',
            11 => '移动失败'
        );
        
        public function upload($key){
            if(!isset($_FILES[$key])){
                return false;
            }
            $f = $_FILES[$key];
            
            //检验文件有没有上传
            if($f['error']){
                $this->errno = $f['error'];
                return false;
            }
            $ext = $this->getExt($f['name']);
            if(!$this->isAllowExt($ext)){
                $this->errno = 8;
                return false;
            }
            if(!$this->isAllowSize($f['size'])){
                $this->errno = 9;
                return false;
            }
            
            $dir = $this->mk_dir();
            if($dir == false){
                $this->errno = 10;
                return false;
            }
            $newname = $this->randName().'.'.$ext;
            $dir = $dir . '/' . $newname;
            if(!move_uploaded_file($f['tmp_name'], $dir)){
                $this->errno = 11;
                return false; 
            }
//            return str_replace('/var/www/data/', '', $dir);
            return $dir;
        }
        
        public function setExt($exts){
            $this->allowExt = $exts;
        }
        
        public function setMaxSize($size){
            $this->maxSize = $size;
        }
        
        public function getErr(){
            return $this->error[$this->errno];
        }
        
        protected function getExt($file){
            $arr = explode('.', $file);
            return end($arr);
        }
        
        protected function isAllowExt($ext){
            return in_array(strtolower($ext), explode(',', $this->allowExt));
        }
        
        protected function isAllowSize($size){
            return $size < $this->maxSize * 1024 * 1024;
        }
        
        protected function mk_dir(){
            $dir = '/var/www/MSTV2.0/data/' . date('Ym/d');
            if(is_dir($dir) || mkdir($dir,0777,true)){
                return $dir;
            }else{
                return false;
            }
        }
        
        protected function randName($length = 6){
            $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
            return substr(str_shuffle($str), 0 ,$length);
        }
    }
?>