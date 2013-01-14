<?php
/**
 * Sample class for files upload
 *
 * @author Rafael Wendel Pinheiro
 * @version 1.0
 * @link https://github.com/rafaelwendel/UploadHelper
 */
class Upload_Helper {
    
    /**
     * The file to be sent
     * @access protected
     * @var array
     */
    protected $_file;
    
    
    /**
     * The extension of file
     * @access protected
     * @var String
     */
    protected $_ext;
    
    
    /**
     * The folder to receive the file
     * @access protected
     * @var String
     */
    protected $_uploads_folder;
    
    
    /**
     * The uploaded file path
     * @access protected
     * @var String
     */
    protected $_file_path;
    
    
    /**
     * The new name of file
     * @access protected
     * @var String
     */
    protected $_file_name = null;
    
    
    /**
     * The allowed extensions in the upload
     * @access protected
     * @var array
     */
    protected $_allowed_exts = array();
    
    
    /**
     * Overwrite file with same name?
     * @access protected
     * @var Boolean
     */
    protected $_overwrite = true;
    
    
    /**
     * The error message
     * @access protected
     * @var String
     */
    protected $_error;
    
    
    /**
     * Constructor method. You can define the file, uploads folder and new file name
     * @access public
     * @param $file File The file to be upload
     * @param $uploads_folder String The path of the receive folder
     * @param $file_name String The new name
     * @return Void
     */
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
    
    
    /**
     * Set a file
     * @access public
     * @param $file File The file to be upload
     * @return Void
     */
    public function set_file($file){
        $this->_file = $file;
        $this->set_ext($file);
    }
    
    
    /**
     * Set the extension of file
     * @access protected
     * @param $file File The file will be sent
     * @return Void
     */
    protected function set_ext($file){
        $this->_ext = strtolower(end(explode('.', $file['name'])));
    }
    
    
    /**
     * Set the folder to receive the file
     * @access public
     * @param $uploads_folder String The path of the receive folder
     * @return Void
     */
    public function set_uploads_folder($uploads_folder){
        if (substr ($uploads_folder, -1) == '/'){
            $this->_uploads_folder = $uploads_folder;
        }
        else{
            $this->_uploads_folder = $uploads_folder . '/';
        }
    }
    
    
    /**
     * Set the new name of the file
     * @access public
     * @param $file_name String The new name
     * @return Void
     */
    public function set_file_name($file_name){
        $this->_file_name = $file_name;
    }
    
    
    /**
     * Set the allowed extensions in the upload
     * @access public
     * @param $file_name String The new name
     * @return Void
     */
    public function set_allowed_exts($allowed_exts){
        if (is_array ($allowed_exts)){
            $this->_allowed_exts = $allowed_exts;
        }
        if (is_string($allowed_exts)){
            $this->_allowed_exts[] = $allowed_exts;
        }
    }
    
    
    /**
     * Overwrite file with same name? (true or false)
     * @access public
     * @param $param Boolean Yes(true) or no(false)
     * @return Void
     */
    public function set_overwrite($param){
        $this->_overwrite = (bool) $param;
    }
    
    
    /**
     * Set a error message
     * @access protected
     * @param $error String The error message
     * @return Void
     */
    protected function set_error($error){
        $this->_error = $error;
    }
    
    
    /**
     * Get the error message
     * @access public
     * @return String The error message
     */
    public function get_error(){
        return $this->_error;
    }
    
    
    /**
     * Get the uploaded file path
     * @access public
     * @return String The uploaded file path
     */
    public function get_file_path(){
        return $this->_file_path;
    }    
    
    
    /**
     * Keep the file with the same name
     * @access protected
     * @return Void
     */
    protected function some_name(){
        $tmp_name = explode('.', $this->_file['name']);        
        unset($tmp_name[count($tmp_name) - 1]);
        
        $this->_file_name = implode('.', $tmp_name);
    }
    
    
    /**
     * Checks whether to overwrite files with the same name. If not, creates name incremented ($name, $name_1, $name_2, $name_n)
     * @access protected
     * @return Void
     */
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
    
    
    /**
     * Validates requirements for uploading
     * @access protected
     * @return Boolean True if valid
     */
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
    
    
    /**
     * Upload the file
     * @access public
     * @return Boolean True if file has been uploaded
     */
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