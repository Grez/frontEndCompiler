# frontEndCompiler
FrontEndCompiler compiles CSS, JS &amp; allows you to copy files from /vendor to public directory (js, css... etc.)
It is little bit similar to [Webloader](https://github.com/janmarek/WebLoader), but FrontEndCompiler allows you to use it on files that are publicly not reachable.

Example usage
------

```yaml
extensions:
    frontEndCompiler: Teddy\FrontEndCompiler\Extension
  
frontEndCompiler:
    imageToDataUrl: true # true|false
    wwwDir: %wwwDir% # public directory (readable from browser)
    tempDir: /temp # temp in wwwDir
    css:
        Teddy:
            dir: %wwwDir%
            files: [/css/style.less]
    js:
        Teddy:
            dir: %wwwDir%
            files: [/js/main.js]
```

```php
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var \Teddy\FrontEndCompiler\Loader @inject */
    public $frontEndCompiler;

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->frontEndCompiler->publicizeDirs();
        $this->template->header = array('css' => '', 'js' => '');
        $this->template->header['css'] = $this->frontEndCompiler->getCss();
        $this->template->header['js'] = $this->frontEndCompiler->getJs();
    }
}
```

```html
<head>
  <meta charset="utf-8">
  <title>Title</title>
  {$header['css']|noescape}
  {$header['js']|noescape}
</head>
```

Which is basically the same thing you can do with Webloader. But what if you want to compile some files you load with composer?
Let's have another `config.neon`, which will be loaded after our first config.

```yaml
frontEndCompiler:
    publicize:
        Teddy:
            dir: %wwwDir%/../vendor/teddy/framework/www
            files: [/images] # compiler will take these folders and copy them to wwwDir (take care so you won't overwrite your files!)
    css:
        Teddy:
            dir: %wwwDir%/../vendor/teddy/framework/www # here we change actual folder of Teddy's CSS
        Game:
            dir: %wwwDir%
            files: [/css/style.less]
    js:
        Teddy:
            dir: %wwwDir%/../vendor/teddy/framework/www
        Game:
            dir: %wwwDir%
            files: [/js/main.js]
```

And Compiler will compile all JS, CSS & copies our folders :)
