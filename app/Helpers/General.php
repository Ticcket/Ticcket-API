<?php
use Illuminate\Support\Facades\Storage;
use App\Models\User;

function uploadImage($img, $title, $disk = 'events') {

    $img_name = time() . '-' . strtolower(str_replace(" ", "_", $title)) . '.' .$img->extension();

    $img->storeAs('/', $img_name, $disk);

    return env("APP_URL") . '/storage/'. $disk . '/'. $img_name;
}

function deleteImage($image, $disk = 'events') {
    $ph = explode("/", $image);
    $ph = end($ph);
    Storage::disk('users')->delete($ph);

}

function isEventOwner($token, $id) {
    $u = User::getUserByToken($token);

    return $u->event()->where('id', $id)->exists();
}
