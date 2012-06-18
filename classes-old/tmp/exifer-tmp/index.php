<html><body>
<?php

// @see http://www.zenphoto.org/trac/browser/trunk/zp-core/exif

include('exif.php');

$path="image.jpg";
$verbose = 0;

$result = read_exif_data_raw($path,$verbose);	
echo "<PRE>"; 
print_r($result); 
echo "</PRE>";
?>

</body></html>