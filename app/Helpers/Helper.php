<?php

use Intervention\Image\Facades\Image as Image;
use App\Models\GlobalSettings;
use App\Http\Controllers\File;

if (!function_exists('setErrorResponse')) {

    function setErrorResponse($message = '', $meta = null) {
        $response = [];
        $response['error']['message'] = $message;
        $response['error']['meta'] = (object) $meta;
        return $response;
    }

}
if (!function_exists('setResponse')) {

    function setResponse($meta = null) {
        $response = [];
        $response['data']=(object) null;
        $response['extra_meta'] = (object) $meta;
        return $response;
    }

}


if (!function_exists('str_slug')) {

    function str_slug($title, $separator = '-', $language = 'en') {
// Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
// Replace @ with the word 'at'
        $title = str_replace('@', $separator . 'at' . $separator, $title);
// Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', $title);
// Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
        $title = strtolower($title);
        return trim($title, $separator);
    }

}

if (!function_exists('bcrypt')) {

    function bcrypt($data) {
        return app('hash')->make($data);
    }

}

if (!function_exists('request')) {

    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null) {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }

}

if (!function_exists('array_flatten')) {

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    function array_flatten($array, $depth = INF) {
//        return Arr::flatten($array, $depth);
        return flatten($array, $depth);
    }

}

/**
 * Flatten a multi-dimensional array into a single level.
 *
 * @param  array  $array
 * @param  int  $depth
 * @return array
 */
function flatten($array, $depth = INF) {
    $result = [];

    foreach ($array as $item) {
        $item = $item instanceof Collection ? $item->all() : $item;

        if (!is_array($item)) {
            $result[] = $item;
        } elseif ($depth === 1) {
            $result = array_merge($result, array_values($item));
        } else {
            $result = array_merge($result, flatten($item, $depth - 1));
        }
    }

    return $result;
}

/**
 * convert image into multiple sizes
 *
 * @param  array  $array
 * @param  int  $depth
 * @return array
 */
function uploadImage($imgObject, $imgPath = "", $filesystem = 'public') {
    try {
        //data:image/png;base64,
        //image size patterm
        $imgSizes = config('constants.images.thumbnail');
        $prefix = time() . "_" . randomString(8);
        //croped image name pattern
        $cropImageName = $prefix . '.' . $imgObject->getClientOriginalExtension();
        //crop image into sizes
        $image = Image::make($imgObject)->resize($imgSizes['height'], $imgSizes['width'])->encode('jpg');
        //upload cropped image
        $path = $imgPath . "/" . $cropImageName;
        Storage::disk($filesystem)->put($path, (string) $image, 'public');
        Storage::copy($path,config('constants.module.quickblox') . "/" . $cropImageName);
        return $cropImageName;
        
    } catch (\Exception $e) {

    }
}

/**
 * file upload on s3 browser
 *
 * @param  array  $array
 * @param  int  $depth
 * @return array
 */
if (!function_exists('S3BucketFileUpload')) {

    function S3BucketFileUpload($file, $path = "") {
        try {
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('s3')->put($path . "/" . $fileName, file_get_contents($file), 'public');
            return $fileName;
        } catch (\Exception $e) {

        }
    }

}

if (!function_exists('uploadBlobImage')) {

    function uploadBlobImage($imgBlobUrl, $imgPath = "") {
        $imageName = time() . "_" . randomString(8) . '.jpeg';
        $path = $imgPath . '/' . $imageName;
        Storage::disk('s3')->put($path, file_get_contents($imgBlobUrl), 'public', fopen($imgBlobUrl, 'r+'));
        return ['imageUrl' => getS3ImageUrl($path),
            'imageName' => $imageName
        ];
        return getS3ImageUrl($path);
    }

}
if (!function_exists('getS3ImageUrl')) {

    function getS3ImageUrl($imgPath = null) {
        $path = ($imgPath != NULL) ? $imgPath : 'profile.png';
        return $url = Storage::disk('s3')->url($path, \Carbon\Carbon::now()->addMinutes(10));
    }

}
if (!function_exists('S3BucketFileRemove')) {

    function S3BucketFileRemove($filePath) {
        if (Storage::disk('s3')->exists($filePath)) {
            Storage::disk('s3')->delete($filePath);
        }
    }

}

//generate random alphanumeric lowercase string
if (!function_exists('randomString')) {

    function randomString($length = "") {
        $length = ($length != "") ? $length : 8;
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, $length);
    }

}

if (!function_exists('format_amount')) {

    /**
     * @param int|float $amount
     * @param int    $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     */
    function format_amount($amount, $decimals = 2, $dec_point = ".", $thousands_sep = ",") {
        return number_format($amount, $decimals, $dec_point, $thousands_sep);
    }

}

if (!function_exists('exportCsv')) {

    /**
     *
     * @param array|json $data
     * @param array $columns
     * @return string filename
     */
    function exportCsv($data, $columns, $encColumns = null) {
        $output = [];
        $data = is_array($data) ? $data : json_decode($data, TRUE);
        $fileName = '../storage/' . time() . '-' . randomString() . '.csv';
        $fp = fopen($fileName, 'w');
        fputcsv($fp, $columns);
        foreach ($data as $raw) {
            foreach ($raw as $key => $value) {
                if ($encColumns && in_array($key, $encColumns)) {
                    $output[$key] = decryption($value);
                } else {
                    $output[$key] = $value;
                }
            }
            fputcsv($fp, $output);
        }
        return $fileName;
    }

}

if (!function_exists('global_config')) {
    /**
     * @param $key
     * Get Global config value
     */
    function global_config($key = null)
    {

        $setting = App\Models\GlobalConfiguration::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return "";
    }
}


if (!function_exists('csvHeaders')) {

    /**
     *
     * @descriotion return csv headers globally
     */
    function csvHeaders() {
        return $headers = [
            "Content-type" => "application/csv",
            "Content-Disposition" => "attachment; filename=" . time() . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    }

}
if (!function_exists('format_date')) {

    /**
     * @param $date
     * @param $format
     * @return false|string
     */
    function format_date($microseconds, $format = null) {
        return date($format ?? config('constants.date_format.us.date'), ($microseconds / 1000));
    }

}

if (!function_exists('format_datetime')) {

    /**
     * @param $date
     * @param $format
     * @return false|string
     */
    function format_datetime($date, $format = null) {
        return \Carbon\Carbon::parse($date)->format($format ?? config('constants.date_format.us.datetime'));
    }

}
if (!function_exists('globalSetting')) {

    /**
     * @param $date
     * @param $format
     * @return false|string
 */
    function globalSetting($name) {
        $value = GlobalSettings::select('value')->where('name', '=', $name)->first();
        return $value->value ?? '';
    }

}
//expiry_duration
if (!function_exists('mail_setting_configure')) {

    function mail_setting_configure() {
        if (\Schema::hasTable('global_settings')) {
            $mail = \DB::table('global_settings')
                            ->whereIn('name', ['mail_smtp_driver', 'mail_smtp_host', 'mail_smtp_port', 'mail_smtp_mail_from_name', 'mail_smtp_mail_from_email', 'mail_smtp_encryption', 'mail_smtp_username', 'mail_smtp_password'])
                            ->pluck('value', 'name')->toArray();
            if (count($mail) > 0) { //checking if table is not empty
                $smtp = [
                    'transport' => $mail['mail_smtp_driver'],
                    'host' => $mail['mail_smtp_host'],
                    'port' => $mail['mail_smtp_port'],
                    'encryption' => $mail['mail_smtp_encryption'],
                    'username' => $mail['mail_smtp_username'],
                    'password' => $mail['mail_smtp_password'],
                ];
                $from = [
                    'name' => $mail['mail_smtp_mail_from_name'],
                    'address' => $mail['mail_smtp_mail_from_email']
                ];
                config(['mail.mailers.smtp' => $smtp]); //update mailers value
                config(['mail.from' => $from]); //update maile from value
            }
        }
    }

}

if (!function_exists('getImageObjectURL')) {

    function getImageObjectURL($file, $path = '') {
        $returnImage = asset('images/placeholder-profile.jpg');
        if ($file != '') {
            try {
                $storage = \Storage::disk(env('FILESYSTEM_DRIVER'));

                if ($storage->exists(trim($path, '/') . '/' . $file)) {
                    $returnImage = $storage->url(trim($path, '/') . '/' . $file);
                }
            } catch (\Exception $e) {
                return $returnImage;
            }
        }
        return $returnImage;
    }

}

if ( ! function_exists('asset')) {
    /**

 * Generate an asset path for the application.
 *
 * @param  string  $path
 * @param  bool    $secure
 * @return string
 */
function asset($path, $secure = null)
{
    return app('url')->asset($path, $secure);
}
}
