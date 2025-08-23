<?php

// /admin/upload/xxxx

return function ($args = null) {
    if(!$_FILES){
        $message = [
            'success' => false, 
            'error' => 'The server refused or did not receive the data (check file size or encoding)',
        ];
        http_out(400, json_encode($message), ['Content-Type' => 'application/json']);
    }
    $upload_to = implode(DIRECTORY_SEPARATOR, $args);
    $base_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . "/asset/image/$upload_to";
    try {
        $result = upload_image($_FILES['avatar'] ?? [], $base_file);
        $result || http_out(400, json_encode(['success' => false, 'error' => 'Invalid file upload']), ['Content-Type' => 'application/json']);

        $result = str_replace($_SERVER['DOCUMENT_ROOT'], '', $result);
        $payload = json_encode(['success' => !!$result, 'url' => $result]);
        http_out(200, $payload, ['Content-Type' => 'application/json']);
    } catch (Throwable $e) {
        http_out(400, json_encode(['success' => false, 'error' => $e->getMessage()]), ['Content-Type' => 'application/json']);
    }
};

function upload_image(array $http_post_file, string $absolute_file_path, ?int $max_kb = null, int $quality = 90): ?string
{
    $_file = payload_guard($http_post_file, $max_kb);
    $gd_image = gd_create($_file)                       ?? throw new BadMethodCallException('Unsupported image type or error creating image resource');
    $gd_image = gd_max_size($gd_image, 1980, 1080)       ?? throw new BadMethodCallException('Error resizing image');

    return save_gd_image($gd_image, $absolute_file_path, $quality);
}

function payload_guard($_file, ?int $max_kb = null)
{
    empty($_file['tmp_name'])              && throw new BadMethodCallException('No file uploaded');
    is_uploaded_file($_file['tmp_name'])   || throw new BadMethodCallException('File is not uploaded via HTTP POST');
    empty($_file['size'])                  && throw new BadMethodCallException('File size is missing');
    empty($_file['type'])                  && throw new BadMethodCallException('File type is missing');

    $max_kb !== null && $_file['size'] > $max_kb * 1024       && throw new BadMethodCallException('File size exceeds the application limit');

    if (!empty($_file['error']) && $_file['error'] !== UPLOAD_ERR_OK) {
        switch ($_file['error']) {
            case UPLOAD_ERR_INI_SIZE:  // Exceeds upload_max_filesize
                $message = "The file is larger than PHP allows (upload_max_filesize).";
                break;
            case UPLOAD_ERR_FORM_SIZE: // Exceeds MAX_FILE_SIZE hidden input (if set)
                $message = "The file is larger than the form allows (". (max_upload()/1024)." kb).";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded.";
                break;
            default:
                $message = "Upload error code: " . $_file['error'];
        }
        throw new BadMethodCallException($message);
    }

    $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_file['tmp_name']);
    $mime === $_file['type']               || throw new BadMethodCallException('File type doesnt match MIME content');

    return $_file;
}

function gd_create(array $_file): ?GdImage
{
    $src = null;
    switch ($_file['type']) {
        case 'image/jpeg':
            $src = imagecreatefromjpeg($_file['tmp_name']);
            break;
        case 'image/png':
            $src = imagecreatefrompng($_file['tmp_name']);
            imagealphablending($src, true);
            imagesavealpha($src, true);
            break;
        case 'image/webp':
            $src = imagecreatefromwebp($_file['tmp_name']);
            break;
    }
    return ($src instanceof GdImage) ? $src : null;
}

function gd_max_size(GdImage $src, int $max_width, int $max_height): ?GdImage
{
    $width = imagesx($src);
    $height = imagesy($src);

    if ($width <= $max_width && $height <= $max_height) {
        return $src; // No resizing needed
    }

    // Calculate new dimensions while maintaining aspect ratio
    if ($width / $height > $max_width / $max_height) {
        $new_width = $max_width;
        $new_height = (int) (($max_width / $width) * $height);
    } else {
        $new_height = $max_height;
        $new_width = (int) (($max_height / $height) * $width);
    }

    // Create a new true color image with the new dimensions
    $dst = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);

    if (!imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height)) {
        imagedestroy($src);
        imagedestroy($dst);
        return null;
    }

    imagedestroy($src);

    return $dst;
}

function save_gd_image(GdImage $gd_image, string $absolute_file_path, int $quality = 90): string
{

    // Get directory and filename without extension
    $folder = dirname($absolute_file_path);

    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
        throw new RuntimeException("Failed to create directory: $folder");
    }

    $target = rtrim($folder, '/\\');
    $target = $target . DIRECTORY_SEPARATOR . pathinfo($absolute_file_path, PATHINFO_FILENAME);
    $candidate = "{$target}.webp";
    if (file_exists($candidate)) {
        $timestamp = (int) (microtime(true) * 1000000);
        if (!rename($candidate, "{$target}-{$timestamp}.webp")) {
            imagedestroy($gd_image);
            throw new RuntimeException("Failed to backup existing file: $candidate");
        }
    }
    // Save the image as WebP
    if (!imagewebp($gd_image, $candidate, $quality)) {
        imagedestroy($gd_image);
        throw new RuntimeException("Failed to save image as WebP: $candidate");
    }

    return ($candidate);
}


function max_upload()
{
    $bytes = function ($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int)$val;
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    };

    return min($bytes(ini_get('upload_max_filesize')), $bytes(ini_get('post_max_size')), $bytes(ini_get('memory_limit')));
}