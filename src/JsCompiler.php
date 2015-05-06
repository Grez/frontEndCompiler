<?php

namespace Teddy\FrontEndCompiler;

use Nette;


class JsCompiler extends Compiler
{

    /**
     * Compiles all .js files into one
     * @return null
     * @throw Nette\FileNotFoundException
     */
    protected function process()
    {
        $js = '';
        foreach ($this->data as $namespace => $config) {
            foreach ($config['files'] as $file) {
                if (file_exists($config['dir'] . $file)) {
                    $js .= file_get_contents($config['dir'] . $file) . "\n\n";
                } else {
                    throw new Nette\FileNotFoundException('File ' . $config['dir'] . $file . ' doesn\'t exist');
                }
            }
        }
        file_put_contents($this->wwwDir . $this->tempDir . '/' . $this->getVersion() . '.js', $js);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return '<script src="/temp/' . $this->getVersion() . '.js"></script>';
    }

}