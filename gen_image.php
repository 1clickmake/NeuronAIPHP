<?php
$img = imagecreatetruecolor(400, 400);
$grey = imagecolorallocate($img, 240, 240, 240);
imagefill($img, 0, 0, $grey);

// Add some text
$darkGrey = imagecolorallocate($img, 150, 150, 150);
imagerectangle($img, 0, 0, 399, 399, $darkGrey);
imagestring($img, 5, 150, 190, "No Image", $darkGrey);

if (!is_dir('public/images')) {
    mkdir('public/images', 0777, true);
}

imagepng($img, 'public/images/no-img.png');
imagedestroy($img);
echo "Image created successfully at public/images/no-img.png";
