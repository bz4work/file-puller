<?php
namespace bz4work\filepuller;

class LoaderRequestTest extends \Codeception\Test\Unit
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

    public function testRequestEmptyUrl()
    {
        $this->expectExceptionMessage('Curl empty response. Check parameters.');
        $this->loader->get_file('', 'some_filename.jpg');
    }

    public function testRequestWrongUrl()
    {
        $this->expectExceptionMessage('Data from Request is not in a recognized format.');
        $this->loader->get_file('https://yii2-cookbook.readthedocs.io/images/cover', 'some_filename.jpg');
    }

    public function testRequest404Url()
    {
        $this->expectExceptionMessage('Curl empty response. Check parameters.');
        $this->loader->get_file('http://404.php.net/', 'some_filename.jpg');
    }

}