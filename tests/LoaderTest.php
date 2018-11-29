<?php
namespace bz4work\filepuller;

class LoaderTest extends \Codeception\Test\Unit
{
    /**
     * @var Loader;
     */
    protected $loader;

    protected function _before()
    {
        $this->loader = new Loader();

        $ds = DIRECTORY_SEPARATOR;
        $path = getcwd() . $ds . 'tests' . $ds . 'data' . $ds . 'image_download';

        $this->loader->setConfig('file_path', $path);
    }

    protected function _after()
    {
        $this->loader = null;
    }

    public function testCreateObject()
    {
        $loader = new Loader();

        $this->assertObjectHasAttribute('config', $loader);
    }


    public function testCommon()
    {
        $this->assertClassHasAttribute('config',Loader::class);
    }

    public function testConfig()
    {
        $config = $this->loader->getConfig();

        $this->assertArrayHasKey('file_path', $config);
        $this->assertNotEmpty($config['file_path']);

        $this->assertArrayHasKey('jpg_quality', $config);
        $this->assertNotEmpty($config['jpg_quality']);
        $this->assertGreaterThanOrEqual(1, $config['jpg_quality']);
        $this->assertLessThanOrEqual(100, $config['jpg_quality']);

        $this->assertArrayHasKey('png_compression', $config);
        $this->assertGreaterThanOrEqual(0, $config['png_compression']);
        $this->assertLessThanOrEqual(9, $config['png_compression']);
    }
}