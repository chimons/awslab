<?php
/**********************************/
/* Provide your S3 Bucket name and region below */

$bucket = 'BUCKET_NAME';
$s3region = 'S3_REGION'; // 'eu-west-1'

/**********************************/


// Activate errors display
if (isset($_GET['debug'])){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// Load the SDK
require('sdk/aws-autoloader.php');




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

				//Instanciate the S3 object from the AWS SDK
				$s3 = new Aws\S3\S3Client([
					'version' => 'latest',
					'region'  => $s3region
				]);

                //Rename the image.
                $actual_image_name = time() . '.' . $ext;

				// Put to S3
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
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$max_filesize = min($max_post, $memory_limit);


?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Wavestone AWS Lab - Upload files to S3</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    </head>
    <body>
        <div class="jumbotron">
            <form action="" method='post' enctype="multipart/form-data">
                <h1 class="display-4">Welcome to Gallery upload page</h1>
                <em><p>Page served from <strong><?php echo(file_get_contents('http://169.254.169.254/latest/meta-data/local-hostname/')); ?></p></strong>
                <p>Uploading to <?php echo $bucket ?> in region <?php echo $s3region ?></p></em>
                <hr class="my-4">
                <p>Max size: <?php echo $max_filesize ?></p>
                <div class="form-group">
                    <input type="file" name="file"/>
                    <input type="submit" value="Upload Image" class="btn btn-primary"/>
                </div>
            </form>

	    <a class="btn btn-success" href="gallery.html" role="button">Open Gallery</a>
            
            <hr class="my-4">

            <?php
            if (isset($image_url)){
                echo '<h3>Image successfully uploaded to Amazon S3</h3>';
                echo '<img src="'.$image_url.'" /><br>';    
                echo '<a href="'.$image_url.'">'.$image_url.'</a><br>';
            }
            ?>

        </div>
    </body>
</html>
