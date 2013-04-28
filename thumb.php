<?

# Get image location
$image_path = $_REQUEST['f'];
$max_width=$_REQUEST['w'];
$max_height=$_REQUEST['h'];



# Load image
$img = null;
$ext = strtolower(end(explode('.', $image_path)));
if ($ext == 'jpg' || $ext == 'jpeg') {
    $img = @imagecreatefromjpeg($image_path);
} else if ($ext == 'png') {
    $img = @imagecreatefrompng($image_path);
# Only if your version of GD includes GIF support
} else if ($ext == 'gif') {
    $img = @imagecreatefromgif($image_path);
}

# If an image was successfully loaded, test the image for size
if ($img) {

    # Get image size and scale ratio
    $width = imagesx($img);
    $height = imagesy($img);
    $scale = min($max_width/$width, $max_height/$height);

    # If the image is larger than the max shrink it
    if ($scale < 1) {
        $new_width = floor($scale*$width);
        $new_height = floor($scale*$height);

        # Create a new temporary image
        $tmp_img = imagecreatetruecolor($new_width, $new_height);

        # Copy and resize old image into new image
        imagecopyresized($tmp_img, $img, 0, 0, 0, 0, 
                         $new_width, $new_height, $width, $height);
        imagedestroy($img);
        $img = $tmp_img;        
    }    
}

# Create error image if necessary
if (!$img) {
    $img = imagecreate($max_width, $max_height);
    imagecolorallocate($img,0,0,0);
    $c = imagecolorallocate($img,70,70,70);
    imageline($img,0,0,$max_width,$max_height,$c2);
    imageline($img,$max_width,0,0,$max_height,$c2);
}

# Display the image
header("Content-type: image/jpeg");
imagejpeg($img,"",100);

?>