<?php namespace bz4work\filepuller;

class LoaderTest extends \Codeception\Test\Unit
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }


    public function testConfig()
    {
        $loader = new Loader();

        echo '<pre>';print_r( $loader->getConfig() );echo '</pre>';exit("\n in:".__FILE__.':'.__LINE__);



    }
}