# CIImageCropper
Image cropper integrate with codeigniter

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

