<?PHP

// echo basename(__DIR__)."<br>";
// echo realpath('index.php')."<br>";
// echo realpath(__DIR__)."<br>";
// exit();

        $message = '';
        $vids = '';
        $imgs = '';
    if(isset($_POST['submit'])){
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fullFileName = $_FILES['file']['name'];
        $fileNameParts = explode('.',$fullFileName);
        $fileExtension = $fileNameParts[count($fileNameParts)-1];
        $fileName = $fileNameParts[0];
        $allowedImgExtensions = ['jpg','jpeg','png'];
        $allowedVidExtensions = ['mp4','mkv'];
        if($fileExtension == 'zip'){
            $zip = new ZipArchive();
            $randomName = uniqid('',true);
            $fileNewName = $randomName.".zip";
            // $fileDestination = "uploads/$fileNewName";
            move_uploaded_file($fileTmpName,$fileNewName);
            if($zip -> open($fileNewName) === TRUE) {
                // $randomName = rand(111,999);
                $zip -> extractTo("unzipped_files/".$randomName."/");
                $zip -> close();
                $files = scandir("unzipped_files/$randomName/$fileName/") ;
                // echo "<pre>";
                // print_r($files);
                // echo is_file("a.txt");
                foreach($files as $file){
                    // echo realpath($file."/")."<br>";
                    // echo "--  $file -- <br>";
                    $fName = explode('.',"$file");
                    $fExt = $fName[count($fName)-1];
                    // echo "--  $fExt -- <br>";

                    // if(is_file(basename(__DIR__)."/unzipped_files/$randomName/$file")){
                    //     echo "this one is a file  --  $file<br>";
                    // }
                    // if(is_dir("unzipped_files/$randomName/$file")){
                    //     echo "this one is a dir  -- $file<br>";
                    //     echo "unzipped_files/$randomName/$file<br>";
                    // }
                    if(strlen($file) && in_array($fExt,$allowedImgExtensions)){
                        $imgs .= "<img width='200' class='p-2' src='unzipped_files/$randomName/$fileName/$file'/>";
                    }
                    if(strlen($file) && in_array($fExt,$allowedVidExtensions)){
                        $vids .= "<video width=320 height=240 class='p-2' controls><source src='unzipped_files/$randomName/$fileName/$file'></video>";
                    }
                }
                unlink($fileNewName);

            }
        }else{
            $message = "Please select a zip file";
        }
        // echo "<pre>";
        // print_r($fileNameParts);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.css">
    <title>Unzipper</title>
</head>
<body>
    <div class="container">
        <div class="row pt-3">
            <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="file" >
                    <input type="submit" value="submit" name="submit">
            </form>
        </div>
        <?php echo $message ?>
        <?php echo $imgs ?>
        <div class="container">
        <?php echo $vids ?>
        </div>
          
  </div>
</body>
</html>