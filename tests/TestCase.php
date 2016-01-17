<?php

use Faker\Factory as Faker;

class TestCase extends PHPUnit_Framework_TestCase {

    /**
     * Faker instance
     */
    private $fake;
    private $times = 1;

    public function setUp()
    {
    	parent::setUp();

        $this->fake = Faker::create();
    }

    public function tearDown()
    {
        parent::tearDown();

        $directory = $this->stubsDirectory();
        $files = scandir($directory);

        for($i = 2; $i < count($files); $i++) {
            unlink($directory . DIRECTORY_SEPARATOR . $files[$i]);
        }

    }

	public function times($times)
    {
        $this->times = $times;

        return $this;
    }

    public function createFile($extension = null)
    {
        $directory = $this->stubsDirectory();
        $ext = $extension ?: '.txt';

        while($this->times --) {
            file_put_contents(
                $directory . $this->fake->word . $ext,
                $this->fake->sentence(5)
            );
        }

        $files = scandir($directory);
        unset($files[0], $files[1]);

        return $files;
    }

    public function baseDirectory()
    {
        return getcwd();
    }

    public function stubsDirectory()
    {
        return $this->baseDirectory() . '/tests/Stubs/';
    }
}