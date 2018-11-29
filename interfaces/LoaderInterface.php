<?php

namespace bz4work\filepuller\interfaces;


interface LoaderInterface{

    public function print_file($filename);

    public function get_file($url, $filename);

}