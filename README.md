UploadHelper
============

A simple helper for upload files

- Easy to use
- Filter extensions

Installation
------------

Download the class on GitHub and add to your project

Guide
------------

### Import

Include the class file path and create a instance

    <?php
        include ('UploadHelper.php');
        $upload = new Upload_Helper();

### Simple Upload

Set a file

    //After a form submit
    $file = $_FILES['file'];
    $upload->set_file($file);

Set the folder to receive the file

    $upload->set_uploads_folder('path/to/uploads/folder'); //the relative path

Set the allowed extensions in the upload

    $allowed = array('jpg', 'png', 'gif', 'bmp');
    $upload->set_allowed_exts($allowed);

Want to rename the file? (The default is to keep the same name)

    $upload->set_file_name('new_name'); //The new name of file

Do you want overwrite file with same name? (true or false) - True is default

    $upload->set_overwrite(false); // Do not overwrite files with the same name

How to limit the size of the files? (2MB is default)

    $upload->set_max_size(5); //Set the size in megabytes

Upload the file

    if($upload->upload_file()){
        echo 'File has been uploaded to ' . $upload->get_file_path(); 
    }
    else{
        echo $upload->get_error();
    }

### Extras

Define the language - English (en) or Portuguese (pt)

    $upload->set_language('pt'); // Define the language of messages to portuguese (English is default)