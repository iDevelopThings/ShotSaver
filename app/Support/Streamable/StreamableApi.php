<?php
/**
 * Created by PhpStorm.
 * User: Sam8t
 * Date: 03/03/2019
 * Time: 8:02 AM
 */

namespace App\Support\Streamable;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class StreamableApi
{

    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.streamable.com']);
    }

    /**
     * Upload a video file to streamable
     *
     * @param $file
     *
     * @return mixed|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile($file)
    {
        try {
            $response = $this->client->request('POST', '/upload', [
                'auth'      => ['sam@idevelopthings.com', env('STREAMABLE_KEY')],
                'multipart' => [
                    ['name' => 'file', 'contents' => file_get_contents($file)],
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $e;
        }
    }

    public function uploadFileFromUrl($fileUrl)
    {
        try {
            $response = $this->client->request('GET', '/import?url='.$fileUrl, [
                'auth'      => ['sam@idevelopthings.com', env('STREAMABLE_KEY')],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Get oembed information for a streamable file
     *
     * @param $file | URL to a streamable video
     *
     * @return mixed|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOembed($file)
    {
        try {
            $response = $this->client->request('GET', 'https://api.streamable.com/oembed.json?url=' . $file, [
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Get information about the upploaded video
     *
     * @param $shortcode | The streamable file shortcode
     *
     * @return mixed|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVideoInfo($shortcode)
    {
        try {
            $response = $this->client->request('GET', '/videos/' . $shortcode, [
                'auth' => ['sam@idevelopthings.com', env('STREAMABLE_KEY')],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw $e;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw $e;
        }
    }

}