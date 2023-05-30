<?php

$filename = 'user_images\sample.jpg';

$exif = exif_read_data($filename, 'EXIF', true);

$timestamp = strtotime($exif['EXIF']['DateTimeOriginal']);
$formattedDate = date("F d, Y", $timestamp);
$formattedTime = date("h:ia", $timestamp);

$dateTaken = "$formattedDate at $formattedTime";

// echo "user_images\sample.jpg:<br />\n";
// $exif = exif_read_data('user_images\sample.jpg', 'IFD0');
// echo $exif===false ? "No header data found.<br />\n" : "Image contains headers<br />\n";

// $exif = exif_read_data('user_images\sample.jpg', 0, true);
// echo "user_images\sample.jpg:<br />\n";
// foreach ($exif as $key => $section) {
//     foreach ($section as $name => $val) {
//         echo "$key.$name: $val<br />\n";
//     }
// }


?>