<?php
if (isset($_GET['debug'])){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

require('sdk/aws-autoloader.php');

$bucket = '<BUCKET_NAME>';
$s3region = '<S3_REGION>'; // 'eu-west-1'

		
function getExtension($str)
{
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l   = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

$valid_formats = array(
    "jpg",
    "png",
    "gif",
    "bmp",
    "jpeg",
    "PNG",
    "JPG",
    "JPEG",
    "GIF",
    "BMP"
);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $tmp  = $_FILES['file']['tmp_name'];
    $ext  = strtolower(getExtension($name));
    
    if (strlen($name) > 0) {
        
        if (in_array($ext, $valid_formats)) {
            
				$s3 = new Aws\S3\S3Client([
					'version' => 'latest',
					'region'  => $s3region
				]);
				
                //Rename image. 
                $actual_image_name = time() . '.' . $ext;
				
				$result = $s3->putObject([
					'Bucket' => $bucket,
					'Key'    => $actual_image_name,
					'SourceFile'   => $tmp,
					'ContentType' => 'image/'.$ext,
					'ACL' => 'public-read'
				]);
				
				$image_url = $result['ObjectURL'];

        } else
            $msg = "Invalid file, please upload image file.";
        
    } else
        $msg = "Please select image file.";
    
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wavestone AWS Lab - Upload files to S3</title>
</head>
<body>
<form action="" method='post' enctype="multipart/form-data">
<h3>Upload image file here</h3><br/>
<div>
<input type='file' name='file'/>
<input type='submit' value='Upload Image'/>
</div>
</form>
<?php
if (isset($image_url)){
	echo '<h3>Image successfully uploaded to Amazon S3</h3>';
	echo '<img src="'.$image_url.'" /><br>';	
	echo '<a href="'.$image_url.'">'.$image_url.'</a><br>';
}
?>

<a href="gallery.html">Open gallery</a>
</body>
</html>

