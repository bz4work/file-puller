<?php

namespace bz4work\filepuller;

use bz4work\filepuller\interfaces\LoaderInterface;

/**
 * Class FileLoader
 * @package bz4work\filepuller
 */
class Loader implements LoaderInterface
{
    private $config;

    /**
     * Loader constructor.
     * @param string $config_file
     */
    public function __construct($config_file = 'config/main.php')
    {
        $this->config = require __DIR__ .DIRECTORY_SEPARATOR. $config_file;
        return $this;
    }

    /**
     * Get all configs.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set new or override old config param.
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setConfig($name, $value)
    {
        $this->config[$name] = $value;
        return $this->config[$name];
    }

    /**
     * Print file in browser.
     *
     * @param $full_path_file
     */
    public function print_file($full_path_file)
    {
        $path_parts = pathinfo($full_path_file);

        $ext = strtolower($path_parts['extension']);
        $header1 = "Content-Type: image/$ext";

        header($header1);

        readfile($full_path_file);
    }

    /**
     * Download and save file.
     *
     * @param $url
     * @param $filename
     * @return string
     * @throws \Exception
     */
    public function get_file($url, $filename)
    {
        $response = $this->make_request($url);

        $file_path = $this->save_file($filename, $response['body']);

        return $file_path;
    }

    /**
     * Execute request via cURL.
     *
     * @param $to_url
     * @return array
     * @throws \Exception
     */
    protected function make_request($to_url)
    {
        $ch = curl_init($to_url);

        //timeout 20 sec
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $data = curl_exec($ch);

        $response = [
            'headers' => null,
            'body' => null
        ];

        if(empty($data)){
            throw new \Exception('Curl empty response. Check parameters.');
        }

        list($headers, $response['body']) = explode("\r\n\r\n", $data);

        //prepare headers
        $headers = explode("\r\n", $headers);
        array_walk($headers, function($item, $key)use(&$response){
            if(strpos($item, 'HTTP') !== false){
                $parts = explode(' ', $item);
                $response['headers']['Status'] = trim($parts[1]);
            }else{
                $parts = explode(':', $item);
                $response['headers'][$parts[0]] = trim($parts[1]);
            }
        });

        //Close handler
        curl_close($ch);

        return $response;
    }

    /**
     * Save downloaded file into folder.
     *
     * @param $filename
     * @param $file_data
     * @return string
     * @throws \Exception
     */
    protected function save_file($filename, $file_data)
    {
        if(empty($filename)){
            throw new \Exception('Filename cannot be empty.');
        }

        if(empty(pathinfo($filename)['extension'])){
            throw new \Exception('Filename must contain an extension.');
        }

        if(!is_writable($this->config['file_path'])){
            throw new \Exception('Destination folder not allowed to writable. Please check permissions.');
        }

        $path = $this->config['file_path'] . DIRECTORY_SEPARATOR . $filename;

        //if file exists - add timestamp to name
        if(file_exists($path)){
            $parts = pathinfo($path);
            $path = $parts['dirname'] .DIRECTORY_SEPARATOR. $parts['filename'] .'-'. time().'.'.$parts['extension'];
        }

        $type = pathinfo($path)['extension'];

        try{
            $im = imagecreatefromstring($file_data);
        }catch (\Exception $e){
            throw new \Exception('Data from Request is not in a recognized format.');
        }

        if ($im === false) {
            throw new \Exception('Could not create image.');
        }

        switch ($type){
            case 'jpg':
            case 'jpeg': imagejpeg($im, $path, $this->config['jpg_quality']); break;
            case 'gif':  imagegif($im, $path); break;
            case 'png':  imagepng($im, $path, $this->config['png_compression']); break;
            default:     throw new \Exception('Extension not supported.');
        }
        imagedestroy($im);

        return $path;
    }
}