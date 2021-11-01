<?php

use Intervention\Image\Facades\Image;

if (!function_exists('fileUpload')) {
    /**
     * @param $new_file
     * @param $path
     * @param null $old_file_name
     * @param null $width
     * @param null $height
     * @return false|string
     */
    function fileUpload($new_file, $path, $old_file_name = NULL, $width = NULL, $height = NULL)
    {
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, TRUE);
        }

        if (isset($old_file_name) && $old_file_name != "" && file_exists(public_path($path) . $old_file_name)) {
            unlink(public_path($path) . $old_file_name);
        }

        $input['image_name'] = uniqid() . time() . '.' . $new_file->getClientOriginalExtension();
        $imgPath = public_path($path . $input['image_name']);

        $makeImg = Image::make($new_file);
        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            $makeImg->resize($width, $height);
            $makeImg->fit($width, $height);
        }

        if ($makeImg->save($imgPath)) {
            return $input['image_name'];
        }
        return false;
    }
}

if (!function_exists('random_number')) {
    /**
     * @param int $a
     *
     * @return string
     */
    function random_number($a = 10) {
        $x = '123456789';
        $c = strlen($x) - 1;
        $z = '';
        for ($i = 0; $i < $a; $i++) {
            $y = rand(0, $c);
            $z .= substr($x, $y, 1);
        }
        return $z;
    }
}


if (! function_exists('sendResponseError')) {

    /**
     * @param string $errorCode
     * @param string $errorMessage
     * @param string $exceptionMessage
     * @param array $data
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function sendResponseError($errorCode = '', $exceptionMessage = '', $errorMessage = '', $data=[], $errorMessages = [] , $code = 200)
    {
        if (!$errorMessage) {
            $errorMessage = __('Something went wrong.');
        }
        if($exceptionMessage) {
            $errorMessage .= ' '.$exceptionMessage;
        }
        $response = [
            'success' => false,
            'code' => intval($errorCode),
            'message' => $errorMessage
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (!empty($errorMessages)) {
            $response['messages'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}

if (! function_exists('sendSuccessResponse')) {


    function sendSuccessResponse($result, $message, $statusCode = 200)
    {
        if (!empty($result)) {
            $response = [
                'success' => true,
                'message' => $message,
                'data' => $result,
            ];
        } else {
            $response = [
                'success' => true,
                'message' => $message,
            ];
        }

        return response()->json($response, $statusCode);
    }
}
