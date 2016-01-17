<?php namespace FileExplorer;

class FileExplorer
{
    public $directory;

    private $files = [];

    public function __construct($directory = null)
    {
        $this->directory = $directory;
    }

    /**
     * List all the files in the directory specified
     *
     * @param string $directory
     *
     * @return $this
     */
    public function listFiles($directory = null)
    {
        if ( !is_null($directory) ) {
            $this->directory = $directory;
        }

        $files = scandir($directory ?: $this->directory);

        // first two indices of the scandir function returns '.' & '..'
        // We remove them from the array before returning it.
        unset($files[0], $files[1]);
        $this->files = array_values($files);

        return $this;
    }

    /**
     * Filter files in the directory by it's extension
     *
     * @param string $extension
     * @param string $dir
     *
     * @return array
     */
    public function filterByExtension($extension, $dir = null)
    {
        $sanitizedExtension = $this->sanitize($extension);
        $filesInDirectory = !empty($this->files) ? $this->files : $this->listFiles($dir)->files;

        return array_filter($filesInDirectory, function ($file) use ($sanitizedExtension) {
            return $sanitizedExtension == stristr($file, '.');
        });
    }

    /**
     * Number of files found
     *
     * @return int
     */
    public function count()
    {
        return count($this->files);
    }

    /**
     * Sanitize the file extension by adding a leading '.' to it
     *
     * @param $extension
     *
     * @return string
     */
    private function sanitize($extension)
    {
        if ( stripos($extension, '.') == 0 ) {
            return '.' . $extension;
        }

        return $extension;
    }

}