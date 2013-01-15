<?php
/**
 * Simple class for files upload
 *
 * @author Rafael Wendel Pinheiro
 * @version 1.0
 */
class Upload_Helper {
    
    protected $_file;
    protected $_ext;
    protected $_uploads_folder;
    protected $_file_path;
    protected $_file_name = null;
    protected $_allowed_exts = array();
    protected $_overwrite = true;
    protected $_error;
    
    public function __construct($file = '', $uploads_folder = '', $file_name = '') {
        if (isset ($file)){
            $this->set_file($file);
        }        
        if (isset ($uploads_folder)){
            $this->set_uploads_folder($uploads_folder);
        }        
        if (isset ($file_name)){
            $this->set_file_name($file_name);
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
        if (substr ($uploads_folder, -1) == '/'){
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
        if (is_array ($allowed_exts)){
            $this->_allowed_exts = $allowed_exts;
        }
        if (is_string($allowed_exts)){
            $this->_allowed_exts[] = $allowed_exts;
        }
    }
    
    public function set_overwrite($param){
        $this->_overwrite = (bool) $param;
    }
    
    protected function set_error($error){
        $this->_error = $error;
    }
    
    public function get_error(){
        return $this->_error;
    }
    
    public function get_file_path(){
        return $this->_file_path;
    }    
    
    protected function some_name(){
        $tmp_name = explode('.', $this->_file['name']);        
        unset($tmp_name[count($tmp_name) - 1]);
        
        $this->_file_name = implode('.', $tmp_name);
    }
    
    protected function verify_overwrite(){
        if (!$this->_overwrite){
            $tmp_name = $this->_file_name;
            $x = 1;
            
            while (file_exists($this->_uploads_folder . $this->_file_name . '.' . $this->_ext)){
                $this->_file_name = $tmp_name . '_' . $x;
                $x++;
            }
        }
    }
    
    protected function is_valid(){
        if (empty ($this->_file['name'])){
            $this->set_error('File is not set');
            return false;
        }
        if (empty ($this->_uploads_folder)){
            $this->set_error('Uploads folder is not set');
            return false;
        }
        if (!in_array ($this->_ext, $this->_allowed_exts)){
            $this->set_error('Files of type ' . $this->_ext . ' are not allowed');
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
        
        $this->verify_overwrite();
        
        if (move_uploaded_file ($this->_file['tmp_name'], $this->_uploads_folder . $this->_file_name . '.' . $this->_ext)){
            $this->_file_path = $this->_uploads_folder . $this->_file_name . '.' . $this->_ext;
            return true;
        }
        else{
            $this->set_error('Error when uploading');
            return false;
        }
    }    
}