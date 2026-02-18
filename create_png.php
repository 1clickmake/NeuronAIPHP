<?php
$data = 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAAY0lEQVR42u3BAQ0AAADCoPdPbQ8HFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB8G8CkAAE6uE67AAAAAElFTkSuQmCC';
if (!is_dir('public/images')) {
    mkdir('public/images', 0777, true);
}
file_put_contents('public/images/no-img.png', base64_decode($data));
echo "PNG created successfully";
