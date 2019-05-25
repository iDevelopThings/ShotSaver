<?php

namespace App\Http\Controllers;

use finfo;
use Illuminate\Http\Request;

class FileStreamingController extends Controller
{
    public function stream()
    {
        $path = \storage_path('app/public/video.mp4');

        // Determine file mimetype
        $finfo = new finfo(FILEINFO_MIME);
        $mime  = $finfo->file($path);

        // Set response content-type
        header('Content-type: ' . $mime);

        // File size
        $size = filesize($path);

        // Check if we have a Range header
        if (isset($_SERVER['HTTP_RANGE'])) {
            // Parse field value
            list($specifier, $value) = explode('=', $_SERVER['HTTP_RANGE']);

            // Can only handle bytes range specifier
            if ($specifier != 'bytes') {
                header('HTTP/1.1 400 Bad Request');

                return;
            }

            // Set start/finish bytes
            list($from, $to) = explode('-', $value);
            if (!$to) {
                $to = $size - 1;
            }

            // Response header
            header('HTTP/1.1 206 Partial Content');
            header('Accept-Ranges: bytes');

            // Response size
            header('Content-Length: ' . ($to - $from));

            // Range being sent in the response
            header("Content-Range: bytes {$from}-{$to}/{$size}");

            // Open file in binary mode
            $fp        = fopen($path, 'rb');
            $chunkSize = 8192; // Read in 8kb blocks

            // Advance to start byte
            fseek($fp, $from);

            // Send the data
            while (true) {
                // Check if all bytes have been sent
                if (ftell($fp) >= $to) {
                    break;
                }

                // Send data
                echo fread($fp, $chunkSize);

                // Flush buffer
                ob_flush();
                flush();
            }
        } /*else {
            // If no Range header specified, send everything
            header('Content-Length: ' . $size);

            // Send file to client
            readfile($path);
        }*/
    }

    public function view()
    {
        return view('stream');
    }

    public function viewother()
    {
        return view('stream2');
    }
}
