#IMDBi
![Banner 720x250](http://up.vbiran.ir/uploads/28106145555976142631_banner-720x250.jpg)
<br/><br/>
**IMDBi** is a wordpress plugin to receive movie/series information using omdbapi(http://www.omdbapi.com) which provides information from Imdb
<br/>
**[Function Reference](https://github.com/iazami/imdbi/wiki/Function-Reference)**

### Features
* Ajax Searching.
* custom poster size.
* editable search results.
* store informations as meta box.
* display informations using shortcode.
* available in both english and persian.
* Using thumbnail when poster is not available.
* Uploading poster automatically via url and save it as thumbnail.

## Installation
There is various ways for installing the plugin.

1. [Download the latest developer version](https://github.com/iazami/imdbi/archive/master.zip) from GitHub, copy & paste it to `wp-content/plugins/` directory. Then activate it through WordPress managment.
2. Clone the repository into `wp-content/plugins/` directory and then activate it through WordPress managment.
3. [Download latest production version](https://wordpress.org/plugins/imdbi) from WordPress official repository.

##### Requirements:
* PHP >= 5.3
* WordPress >= 3.8

## Translations
Translations welcome! Translators name will credited in WordPress repository page.

To translate the plugin:

1. Fork the repository
2. Translate strings with poEdit or anyother software you want.
3. Send a pull request


## Changelog
**Version 2.0.0-beta:**
* new: refactor entire source code
* new: plugin is now available in both english and persian.
* new: improving meta box UI.
* new: uploading poster automatically via url.
* new: custom poster size.
* fixed: search results are now editable.
* fixed: ajax search crashing.
* fixed: crawler crash causes.
* fixed: leaking out data.
* fixed: shortcode issues.
* fixed: `imdbi()` is now expecting one argument.
* fixed: `imdbi('poster')` is now only return poster url.
* fixed: `imdbi_check()` is now expecting meta box name as argument ( eg: `imdbi_check('year')` ).
