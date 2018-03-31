<?php

function supported_image( $file ) {
    $len = strlen($file);
    $f_type = substr( $file, $len-3, 3 );
    return $f_type == 'JPG' || $f_type == "jpg";
}

function find_thumbnail( $site, $jour )
{
    $fullpath = $site . "/" . $jour . "/thumbs/in.jpg";
    if( is_file( $fullpath ) ) {
        return $fullpath;
    }
    $fullpath = $site . "/" . $jour . "/in.jpg";
    if( is_file( $fullpath ) ) {
        return $fullpath;
    }

    $thumbpath = $site . "/" . $jour . "/thumbs";
    if( is_dir( $thumbpath ) ) {
        $rep=opendir( $thumbpath );
        while( $file = readdir( $rep ) ) {
            $fullpath = $thumbpath . "/" . $file;
            if( is_file( $fullpath ) ) {
                if( supported_image( $file ) == true ) {
                    return $fullpath;
                }
            }
        }
        closedir($rep);
    }
}


function makeSmallerImage($filePath, $thumbPath, $small_width, $small_height ) {

    $quality = 85;    
// Get the image dimensions.
    $dimensions = @getimagesize($filePath);
    $width        = $dimensions[0];
    $height        = $dimensions[1];
    $smallerSide = min($width, $height);
    if( $width < $height ) {
        $small_width = $width / $height * $small_height;
    } else {
        $small_height = $height / $width * $small_width;
    }
    $deltaX = 0;
    $deltaY = 0;
    // get image identifier for source image
    $imageSrc  = @imagecreatefromjpeg($filePath);
    $imageDest = @imagecreatetruecolor($small_width, $small_height);
    $success = @imagecopyresampled($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $small_width, $small_height, $width, $height);
    if( ! $success ) {
    return false;
    }
    // save the thumbnail image into a file.
    $success = @imagejpeg($imageDest, $thumbPath, $quality);

    // Delete both image resources.
    @imagedestroy($imageSrc);
    @imagedestroy($imageDest);                        

    return $success;
}
  
function makeThumb( $filePath, $thumbPath ) {
    return makeSmallerImage( $filePath, $thumbPath, 150, 150 );
}

function smallImage( $filePath, $thumbPath ) {
    return makeSmallerImage( $filePath, $thumbPath, 600, 600 );
}

function rotateImage( $filePath, $alpha ) {
    if( is_file( $filePath ) ) {
        $imageSrc = @imagecreatefromjpeg( $filePath );
	$imageRot = @imagerotate( $imageSrc, $alpha, 0 );
	$quality=85;
	$success = @imagejpeg($imageRot, $filePath, $quality);

	@imagedestroy($imageSrc);
	@imagedestroy($imageRot);
    }
}

?>

