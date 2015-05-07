<?php

namespace Teddy\FrontEndCompiler;

use Nette;


abstract class Compiler
{

    /** @var string */
    protected $tempDir = '';

    /** @var string */
    protected $wwwDir = '';

    /** @var array */
    protected $data = array();


    public function __construct($tempDir, $wwwDir)
    {
        $this->tempDir = $tempDir;
        $this->wwwDir = $wwwDir;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    protected function getCacheFilename()
    {
        $reflection = new \ReflectionClass(get_class($this));
        return $this->wwwDir . $this->tempDir . '/' . $reflection->getShortName();
    }

    /**
     * @return bool
     */
    protected function isCacheActual()
    {
        if (!file_exists($this->getCacheFilename())) {
            return false;
        }

        return (filemtime($this->getCacheFilename()) >= $this->getVersion());
    }

    /**
     * @return int
     */
    protected function getVersion()
    {
        $version = 0;
        foreach ($this->data as $namespace => $config) {
            foreach ($config['files'] as $file) {
                if (file_exists($config['dir'] . $file)) {
                    $version = max($version, filemtime($config['dir'] . $file));
                }
            }
        }
        return $version;
    }

    /**
     * Updates cache
     * @return null
     */
    protected function updateCache()
    {
        touch($this->getCacheFilename());
    }

    /**
     * If cache is old -> compiles files and updates cache
     */
    public function compile()
    {
        if (!$this->isCacheActual()) {
            $this->process();
            $this->updateCache();
        }
    }

    abstract protected function process();

}