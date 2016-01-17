<?php

use Faker\Factory as Faker;

class FileExplorerTest extends TestCase
{
    private $times = 1;

    private $fake;

    public function setUp()
    {
        parent::setUp();

        $this->fake = Faker::create();
    }

    public function tearDown()
    {
        parent::tearDown();

        $directory = $this->baseDirectory() . '/tests/Unit/Stubs/';
        $files = scandir($directory);

        for($i = 2; $i < count($files); $i++) {
            unlink($directory . DIRECTORY_SEPARATOR . $files[$i]);
        }

    }

    /** @test */
    public function it_is_instantiable()
    {
        $fileExplorer = new FileExplorer\FileExplorer;

        $this->assertInstanceOf(FileExplorer\FileExplorer::class, $fileExplorer);
    }

    /** @test */
    public function it_lists_the_files_available_in_a_directory()
    {
        $this->times(2)->createFile();
        $directory = $this->baseDirectory() . '/tests/Unit/Stubs/';

        $files = ( new FileExplorer\FileExplorer )->listFiles($directory);

        $this->assertEquals(2, $files->count());
    }

    /** @test */
    public function it_accepts_the_directory_name_via_the_constructor()
    {
        $directory = $this->baseDirectory() . '/tests/Unit/Stubs/';
        $filesClass = new FileExplorer\FileExplorer($directory);

        $this->assertEquals(
            $directory,
            $filesClass->directory
        );
    }

    /** @test */
    public function it_filters_files_in_a_directory_based_on_the_extension()
    {
        $directory = $this->baseDirectory() . '/tests/Unit/Stubs/';
        $excelFiles = $this->times(3)->createFile('.xlsx');
        $regularFiles = $this->times(2)->createFile();

        $filesClass = new FileExplorer\FileExplorer($directory);

        $filesByExtension = $filesClass->filterByExtension('xlsx');

        $this->assertEquals(3, count($filesByExtension));
    }

    /** @test */
    public function it_allows_methods_to_be_chained()
    {
        $this->times(3)->createFile('.xlsx');
        $filesClass = new FileExplorer\FileExplorer();

        $files = $filesClass->listFiles($this->baseDirectory() . '/tests/Unit/Stubs/')
                            ->filterByExtension('xlsx');


        $this->assertEquals(3, count($files));
    }

    private function times($times)
    {
        $this->times = $times;

        return $this;
    }

    private function createFile($extension = null)
    {
        $directory = $this->baseDirectory() . '/tests/Unit/Stubs/';
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

    private function baseDirectory()
    {
        return getcwd();
    }
}