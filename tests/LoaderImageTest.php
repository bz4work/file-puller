<?php
namespace bz4work\filepuller;

class LoaderImageTest extends \Codeception\Test\Unit
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

    public function testGetFileEmptyFilename()
    {
        $this->expectException(\Exception::class);

        $this->expectExceptionMessage('Filename cannot be empty.');
        $this->loader->get_file('https://yii2-cookbook.readthedocs.io/images/cover.jpg', '');
    }

    public function testGetFileFilenameWithoutExtension()
    {
        $this->expectException(\Exception::class);
        $this->loader->get_file('https://yii2-cookbook.readthedocs.io/images/cover.jpg', 'some_filename');
    }

    public function testGetFileJpg()
    {
        $file = $this->loader->get_file('https://yii2-cookbook.readthedocs.io/images/cover.jpg', 'some_filename.jpg');

        $this->assertFileExists($file);
        $this->assertFileIsReadable($file);
    }

    public function testGetFilePng()
    {
        $file = $this->loader->get_file('https://assets-cdn.github.com/images/modules/site/integrators/slackhq.png', 'slackhq.png');

        $this->assertFileExists($file);
        $this->assertFileIsReadable($file);
    }

    public function testGetFileGif()
    {
        $file = $this->loader->get_file('https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Rotating_earth_%28large%29.gif/267px-Rotating_earth_%28large%29.gif', 'earth.gif');

        $this->assertFileExists($file);
        $this->assertFileIsReadable($file);
    }

    public function testGetFileAnotherExtension()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Extension not supported.');

        $this->loader->get_file('https://assets-cdn.github.com/images/modules/site/integrators/slackhq.png', 'slackhq.xml');
    }
}