<?php

namespace Teddy\FrontEndCompiler;

use Nette;


class PublicizeFilesCompiler extends Compiler
{

    protected function process()
    {
        foreach ($this->data as $namespace => $config) {
            foreach ($config['files'] as $file) {
                $this->recurseCopy($config['dir'] . $file, $this->wwwDir . $file);
            }
        }
    }

    /**
     * Copies dir|file from $src to $dst
     * @param $src
     * @param $dst
     */
    protected function recurseCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.' ) && ($file != '..' )) {
                if (is_dir($src . '/' . $file) ) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

}