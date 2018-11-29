File Puller
===========
Loading remote file across HTTP protocol. 


Requirements:
------------
1. php 7.2.0
2. php-gd
3. php-curl


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bz4work/file-puller "@dev"
```

or add

```
"bz4work/file-puller": "@dev"
```

to the require section of your `composer.json` file.

Add to your root composer.json, in the 'repositories' section:

    {
        "type": "github",
        "url": "https://github.com/bz4work/file-puller.git"
    }

Usage
-----

1. Create a 'image_download' folder in the root directory of your site that is accessible to the web 
(like public_html):
    
    
    image_download
There will be stored uploaded files.

2. Give write access to this folder.

3. Use in your code:
```php
$loader = new Loader();
$name_new_file = 'red_php.jpg';
$image = $loader->get_file('https://c1.staticflickr.com/1/411/19722115906_3030f88c9a_n.jpg', $name_new_file);

$loader->print_file($image);//print file if needed
```
4. If you use without Yii2:

    
    0. Install Composer for your OS (if not yet installed).
    1. Unzip plugin in your www directory.
    2. Create folder 'image_download' in 'www'.
    3. Run 'composer dump-autoload' for create class autoloader.
    4. Paste into your index.php:
    
        <?php
        require __DIR__ . '/vendor/autoload.php';
        use bz4work\filepuller\Loader;
        
        $loader = new Loader();
        $name_new_file = 'red_php.jpg';
        $image = $loader->get_file('https://c1.staticflickr.com/1/411/19722115906_3030f88c9a_n.jpg', $name_new_file);
        
        $loader->print_file($image);//print file if needed
        
        
    


4. Run tests:

    
    Run with code coverage generating:
    ./vendor/bin/codecept run --steps --coverage-html -v
    
    Run without code cover generating:
    ./vendor/bin/codecept run --steps -v