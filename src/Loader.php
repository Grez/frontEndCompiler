<?php

namespace Teddy\FrontEndCompiler;

use Nette;


class Loader
{

    /** @var string */
    protected $wwwDir = '';

    /** @var string */
    protected $tempDir = '';

    /** @var array */
    protected $js = array();

    /** @var array */
    protected $css = array();

    /** @var bool */
    protected $convertToDataUrl = false;

    /** @var array */
    protected $publicize = array();


    /**
     * @param string $wwwDir
     * @return null
     */
    public function setWwwDir($wwwDir)
    {
        $this->wwwDir = $wwwDir;
    }

    /**
     * @param string $tempDir
     * @return null
     */
    public function setTempDir($tempDir)
    {
        $this->tempDir = $tempDir;
    }

    /**
     * @param array $css
     * @return null
     */
    public function setCss($css)
    {
        $this->css = $css;
    }

    /**
     * @param bool $convertToDataUrl
     * @return null
     */
    public function setConvertToDataUrl($convertToDataUrl)
    {
        $this->convertToDataUrl = $convertToDataUrl;
    }

    /**
     * @param array $js
     * @return null
     */
    public function setJs($js)
    {
        $this->js = $js;
    }

    /**
     * @param array $publicize
     * @return null
     */
    public function setPublicize($publicize)
    {
        $this->publicize = $publicize;
    }

    /**
     * Copies folders from /vendor to public folder
     * @return null
     */
    public function publicizeDirs()
    {
        $compiler = new PublicizeFilesCompiler($this->tempDir, $this->wwwDir);
        $compiler->setData($this->publicize);
        $compiler->compile();
    }

    /**
     * Converts .less files, minifies them, converts small imgs to DataUrl
     * @return string <stylesheet>
     */
    public function getCss()
    {
        $compiler = new CssCompiler($this->tempDir, $this->wwwDir);
        $compiler->setConvertToDataUrl($this->convertToDataUrl);
        $compiler->setData($this->css);
        $compiler->compile();
        return $compiler->getLink();
    }

    /**
     * Joins js files together
     * @return string
     */
    public function getJs()
    {
        $compiler = new JsCompiler($this->tempDir, $this->wwwDir);
        $compiler->setData($this->js);
        $compiler->compile();
        return $compiler->getLink();
    }

}