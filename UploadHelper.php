<?php
/**
 * Sample class for files upload
 *
 * @author Rafael Wendel Pinheiro
 * @version 1.0
 */
class Upload_Helper {
    
    protected $_file;
    protected $_ext;
    protected $_uploads_folder;
    protected $_file_name = null;
    protected $_allowed_exts = array();
    protected $_overlap = true;
    
    public function __construct($file = '', $uploads_folder = '') {
        if (isset($file)){
            $this->set_file($file);
        }        
        if (isset ($uploads_folder)){
            $this->set_uploads_folder($uploads_folder);
        }
    }
    
    public function set_file($file){
        $this->_file = $file;
        $this->set_ext($file);
    }
    
    protected function set_ext($file){
        $this->_ext = strtolower(end(explode('.', $file['name'])));
    }
    
    public function set_uploads_folder($uploads_folder){
        if(substr($uploads_folder, -1) == '/'){
            $this->_uploads_folder = $uploads_folder;
        }
        else{
            $this->_uploads_folder = $uploads_folder . '/';
        }
    }
    
    public function set_file_name($file_name){
        $this->_file_name = $file_name;
    }
    
    public function set_allowed_exts($allowed_exts){
        if(is_array ($allowed_exts)){
            $this->_allowed_exts = $allowed_exts;
        }
    }
    
    public function set_overlap($param){
        $this->_overlap = (bool) $param;
    }
    
    protected function some_name(){
        $tmp_name = explode('.', $this->_file['name']);        
        unset($tmp_name[count($tmp_name) - 1]);
        
        $this->_file_name = implode('.', $tmp_name);
    }
    
    protected function is_valid(){
        if (!isset ($this->_file)){
            return false;
        }
        if (!isset ($this->_uploads_folder)){
            return false;
        }
        if (!in_array ($this->_ext, $this->_allowed_exts)){
            return false;
        }
        return true;
    }
    
    public function upload_file(){
        if (!$this->is_valid()){
            return false;
        }
        
        if (!isset ($this->_file_name)){
            $this->some_name();
        }
        
        if (move_uploaded_file ($this->_file['tmp_name'], $this->_uploads_folder . $this->_file_name . '.' . $this->_ext)){
            return true;
        }
        else{
            return false;
        }
    }    
}