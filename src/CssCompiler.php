<?php

namespace Teddy\FrontEndCompiler;

use Nette;
use Teddy\ImgToDataUrl;


class CssCompiler extends Compiler
{

    /** @var bool */
    protected $convertToDataUrl = false;


    /**
     * @param bool $convertToDataUrl
     */
    public function setConvertToDataUrl($convertToDataUrl)
    {
        $this->convertToDataUrl = $convertToDataUrl;
    }

    /**
     * Compiles .less files into .css
     * If allowed tries to convert small images into dataUrl
     * @return null
     * @throw Nette\FileNotFoundException
     */
    protected function process()
    {
        $parser = new \Less_Parser(array('compress' => true));
        foreach ($this->data as $namespace => $config) {
            foreach ($config['files'] as $file) {
                if (file_exists($config['dir'] . $file)) {
                    $parser->parseFile($config['dir'] . $file);
                } else {
                    throw new Nette\FileNotFoundException('File ' . $config['dir'] . $file . ' doesn\'t exist');
                }
            }
        }
        $css = $parser->getCss();

        if ($this->convertToDataUrl) {
            $imgToDataUrl = new ImgToDataUrl($this->wwwDir);
            $imgToDataUrl->setCss($css);
            $css = $imgToDataUrl->convert();
        }

        file_put_contents($this->wwwDir . $this->tempDir . '/' . $this->getVersion() . '.css', $css);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return '<link rel="stylesheet" media="all" href="/temp/' . $this->getVersion() . '.css">';
    }

}