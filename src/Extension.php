<?php

namespace Teddy\FrontEndCompiler;

use Nette;


class Extension extends Nette\DI\CompilerExtension
{

    public function loadConfiguration()
    {
        parent::loadConfiguration();
        $config = $this->getConfig();

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('loader'))
            ->setClass('Teddy\FrontEndCompiler\Loader')
            ->addSetup('setWwwDir', array($config['wwwDir']))
            ->addSetup('setTempDir', array($config['tempDir']))
            ->addSetup('setCss', array($config['css']))
            ->addSetup('setJs', array($config['js']));

        if (isset($config['publicize'])) {
            $builder->getDefinition($this->prefix('loader'))
                ->addSetup('setPublicize', array($config['publicize']));
        }

        if (isset($config['imageToDatUrl'])) {
            $builder->getDefinition($this->prefix('loader'))
                ->addSetup('setImageToDataUrl', array($config['imageToDatUrl']));
        }
    }

}