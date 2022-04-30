<?php


function uploadImage($img, $title, $disk = 'events') {

    $img_name = time() . '-' . strtolower($title) . '.' .$img->extension();

    $img->storeAs('/', $img_name, $disk);

    return 'storage/events/' . $img_name;
}
