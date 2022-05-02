<?php
use Illuminate\Support\Facades\Storage;

function uploadImage($img, $title, $disk = 'events') {

    $img_name = time() . '-' . strtolower($title) . '.' .$img->extension();

    $img->storeAs('/', $img_name, $disk);

    return 'storage/'. $disk . '/'. $img_name;
}

function deleteImage($image, $disk = 'events') {
    $ph = explode("/", $image);
    $ph = end($ph);
    Storage::disk('users')->delete($ph);

}
