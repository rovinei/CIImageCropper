# Image Cropper
Cropping image with javascript using cropper jQuery library, spatie/image-optimizer for image optimization.

# System Server Requirements

## php.ini

1. upload_max_filesize

Sets a maximum amount of memory in bytes that a script is allowed to allocate. Resizing a 3000 x 2000 pixel image to 300 x 200 may take up to 32MB memory.


2. php Extensions and version : 

+ PHP >= 7.0
+ Fileinfo Extension
+ GD Library (>=2.0) … or …
+ Imagick PHP extension (>=6.5.7)

## Image Optimization tools (**Optional**) [Image optimizer](https://github.com/spatie/image-optimizer "spatie/image-optimizer")

1. Here's how to install all the optimizers on Ubuntu:
+ sudo apt-get install jpegoptim
+ sudo apt-get install optipng
+ sudo apt-get install pngquant
+ sudo npm install -g svgo
+ sudo apt-get install gifsicle

2. And here's how to install the binaries on MacOS (using Homebrew):
+ brew install jpegoptim
+ brew install optipng
+ brew install pngquant
+ brew install svgo
+ brew install gifsicle

**Other**
+ brew install --with-openssl curl
+ brew install --with-homebrew-curl --with-apache php71
+ brew install php71-mcrypt php71-imagick

## Integration Step

+ Copy all require dependencies in composer.json file under key **require** and paste into composer.json in your project.
+ Copy all dependencies in package.josn under key **dependencies** into your package.josn file in your project, if your project doesn't have, just copy the whole file paste in root project folder.
+ Check folder **application/libraries/** and copy all those files and paste into the same folder in your project.
+ Copy folder **public/, src/** and files **webpack.config.js, prepros-6.config** paste into your root project folder.
+ cut root file **index.php** in root project and paste by overwrite index.php in public folder, then configure key setting of system, application folder to reflex your project structure.
+ View file for image cropper page > views/cropper.php
+ controller file for image cropper > controllers/Page.php

## Edit post url of $.ajax()
+ Edit file **src/js/index.js line: 144**, or look where is the ajax post url, then change it to **/page/do_upload** to make it matchs with controller route method.

## Install Dependencies
```bash
[ProjectRoot]> composer install
```
```bash
[ProjectRoot]> npm install
```

## Recompile and bundle edited index.js file
+ If you don't have webpack install globally on your system:
```bash
> npm install webpack -g
```
+ Then run following command in project root, **Note: it required webpack.config.js**
```bash
[ProjectRoot]> webpack
```

**Serve your project from public folder, as our root index.php in there**

