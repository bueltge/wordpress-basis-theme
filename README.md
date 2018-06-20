# WordPress Basis Theme
Basis provides a starting point for your theme development.

## Description
Basis has been built to make theme development for WordPress easier. If you are an experienced theme developer or a total beginner, Basis provides a starting point for your theme development and give your the typically development with WordPress by use Hooks. Basis comes with a pre-defined set of templates, no styles or style-framework for pure development. The goal is an starter, not a framework that represent a lot of functions. So it is not necessary to remove topics. Start clean with only what you need with focus to the goal.

Basis has an long tradition, development and maintenance since 2007.
The new version with the codename `namespace` ;) is usable, but changed in the course of time.

* The plugin use hooks, always started with a prefix, set via `Core` class, on default `wp_basis` inside the `Setup` class.
  * also the next string is the string of the template `wp_basis_single_`
  * the next string on the hook logic is 'before' or 'after' an element in the markup, sometimes 'content' - `wp_basis_single_before_content`
* the `functions.php` is current only for loading all files inside the `inc` directory;
  * older solutions do you find in the `classes-old` in branch `stacker`
* The theme supports [OpenSearch](http://www.opensearch.org/Specifications/OpenSearch) so that you should change, customize the `os.xml` file
* The theme supports on default the ['Gutenberg'](https://wordpress.org/gutenberg/handbook/) Editor in a minimal focus, loading a style for the editor and theme support with custom collers, see the class `Gutenberg`.

## Child Theme Usage
See the repository for a [starter kit](https://github.com/bueltge/wordpress-basis-theme-Child-Starter), a example.

## Installation
### Requirements
* WordPress (also Multisite) version 3.3 and later
* PHP 5.6 (use PHP namespaces)

### Composer Usage
#### What is Composer?
Composer is a dependency manager for PHP. Composer will manage the dependencies you require on a project by project basis. This means that Composer will pull in all the required libraries, dependencies and manage them all in one place. For a detailed description see [Composer  Site](https://getcomposer.org/).

#### Installation
The plugin is available as Composer package and can be installed via Composer:
```shell
composer create-project bueltge/wordpress-basis-theme --no-dev`.
```

The package is on [packagist](https://packagist.org/packages/bueltge/wordpress-basis-theme) and the package name is `bueltge/wordpress-basis-theme`.

### Grunt usage
#### What is Grunt?
Grunt is a JavaScript task runner. For a detailed description see [Grunt.js homepage](http://gruntjs.com/).

#### Requirements
* [Node.js](http://nodejs.org/)

#### Installation
It's quite simple. After Node.js installation, just run the following command (in the theme's folder, where the `package.json` file is located):
```shell
npm install
```
The Node Package Management (npm) will read the `package.json` and will install all the packages listed in the `devDependencies` object.

#### Usage
By default, there's one main task configured: `default`. For configuration see `Gruntfile.js`. In the command line, you can run
```shell
grunt
```
Grunt will watch all JavaScript and CSS files within the `assets` folder. If a file is changed and saved, Grunt will run `jshint` for JavaScript files and `cssmin` for CSS files automatically. Feel free to edit the configuration :)

## Other Notes
### Licence
Good news, this theme is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog.

### Contact & Feedback
The theme base is designed and developed current by me ([Frank Bültge](https://bueltge.de)).

Please let me know if you like the theme or you hate it or whatever ... Please fork it, add an issue for ideas and bugs.

### Disclaimer
I'm German and my English might be gruesome here and there. So please be patient with me and let me know of typos or grammatical farts. Thanks
