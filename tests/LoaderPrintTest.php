<?php
namespace bz4work\filepuller;

class LoaderPrintTest extends \Codeception\Test\Unit
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

    public function testPrintFile()
    {
        $file = $this->loader->get_file('https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/MarvelLogo.svg/200px-MarvelLogo.svg.png', 'marvel-logo.png');

        ob_start();
        $this->loader->print_file($file);
        $code = ob_get_flush();

        $this->assertNotEmpty($code);
    }

    public function testPrintFileSameContent()
    {
        $file = $this->loader->get_file('https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/MarvelLogo.svg/200px-MarvelLogo.svg.png', 'marvel-logo.png');

        ob_start();
        $this->loader->print_file($file);
        $code = ob_get_flush();

        $origin_img_code = file_get_contents($file);

        $this->assertEquals($code, $origin_img_code);
    }


}