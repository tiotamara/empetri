<?php 
    $data = array();
    if(isset($_GET['files']))
    {  
        $error = false;
        $files = array();

        $uploaddir = './'.$_POST['playlist'].'/';
        foreach($_FILES as $file)
        {
            if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
            {
                $files[] = $uploaddir .$file['name'];
            }
            else
            {
                $error = true;
            }
        }
        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
    }elseif (isset($_GET['delete'])) {
        // print_r($_POST);
        // die();
        $basedir = $_POST['path'];
         $file_to_delete = $_POST['nama_file'];  

         $path = '.'.$basedir.$file_to_delete;
         // echo $path;
         // die();
         unlink($path);
    }
    else
    {
        $data = array('success' => 'Form was submitted', 'formData' => $_POST);
    }

    echo json_encode($data);
 ?>