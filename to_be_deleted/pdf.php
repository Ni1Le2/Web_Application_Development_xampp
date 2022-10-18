
<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'websitedb';
 
    $con = mysqli_connect($host, $user, $password, $database);
 
    // Check connection
    if($con === false){
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    }
    
    if (isset($_POST['upload'])) {
 
        //$name = $_POST['name'];
 
        if (isset($_FILES['pdf_file']['name']))
        {
        $author =  $_REQUEST['author'];
        $title = $_REQUEST['title'];
        $year =  $_REQUEST['year'];
        $file_name = $_FILES['pdf_file']['name'];
        $file_tmp = $_FILES['pdf_file']['tmp_name'];
 
        move_uploaded_file($file_tmp,"../papers/".$file_name);
          
        $insertquery =
          "INSERT INTO papers(author, title, year, filename) VALUES('$author', '$title', '$year', '$file_name')";
          $iquery = mysqli_query($con, $insertquery);
            // Close connection
        mysqli_close($con);
        
        header("Location: http://127.0.0.1:881/wad/upload_success.php");
        exit();
        }
        else
        {
           ?>
            <div class=
            "alert alert-danger alert-dismissible
            fade show text-center">
              <a class="close" data-dismiss="alert"
                 aria-label="close">×</a>
              <strong>Failed!</strong>
                  File must be uploaded in PDF format!
            </div>
          <?php
        }
    }
?>