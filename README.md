![Latest version](https://img.shields.io/github/v/release/jon48/webtrees-theme-rural)
![Release date](https://img.shields.io/github/release-date-pre/jon48/webtrees-theme-rural)
![Downloads](https://img.shields.io/github/downloads/jon48/webtrees-theme-rural/total)
![License](https://img.shields.io/github/license/jon48/webtrees-theme-rural)

# webtrees-theme-rural
Rural Theme for the **[webtrees](https://webtrees.net/)** application

## Contents

* [License](#license)
* [Introduction](#introduction)
* [System requirements](#system-requirements)
* [Installation / Upgrading](#installation--upgrading)
* [Customising](#customising)
* [Development](#development)
* [Issues / Security](#issues--security)
* [Contacts](#contacts)

### License

* **webtrees-theme-rural: Rural Theme for webtrees**
* Copyright (C) 2009 to 2024 Jonathan Jaubart.
* Derived from **webtrees** - Copyright (C) 2010 to 2024  webtrees development team.
* Derived from PhpGedView - Copyright (C) 2002 to 2010  PGV Development Team.

This program is free software; you can redistribute it and/or modify it under the
terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See the [LICENSE](LICENSE.md) included with this software for more detailed licensing information.


### Introduction

The Rural theme has first been created for PhpGedView in 2009.
It has since then been migrated to **webtrees**, and followed the subsequent evolutions, up to the latest version.
Initially embedded in a larger code base, I have extracted it to its own GitHub repository in 2018.

The Rural theme is mainly based on brown tones, as a tribute to the earth our ancestors used to cultivate, with some green, colour of nature. Contrary to other standard themes which use the full width of the screen, its characteristic layout is organised around the main genealogical content being wrapped within a responsive white panel on a brown background.

The header logo can be customised to any image, as long as its height does not exceed 150px (instructions below).

Having been a loyal companion to my **webtrees** experience for so many years now, I am happy to share the Rural theme and hope it can find its way to your list of themes.

*Jonathan Jaubart*

### System requirements

**webtrees-theme-rural** shares the same requirements and system configuration as a standard **webtrees** installation.

The repository does not implement any browser compatibility testing, and the table below is only based on empirical testing. Please raise an issue if you experience display issues on a supported browser.

| Browser             | Supported          |
| ------------------- | ------------------ |
| Chrome              | :heavy_check_mark: |
| Edge                | :heavy_check_mark: |
| Firefox             | :heavy_check_mark: |
| Internet Explorer   | :x:                |
| Safari (iOS)        | :heavy_check_mark: |


### Installation / Upgrading

The installation or upgrade process follows the standard one for webtrees themes/modules.

#### Versions up to 1.7

Steps:

1. Download the latest release compatible with your **webtrees** version from the [webtrees-theme-rural GitHub repository](https://github.com/jon48/webtrees-theme-rural/releases).
2. Unzip the archive in the root of your webtrees installation. The zip file contains the full structure of folders, and will merge with your current one.
3. Check if the folder `rural` is now present in the `/themes/` directory.
4. Open **webtrees** and the Rural Theme should now be available in the list of themes.

#### Versions from 2.0

Steps:

1. Download the latest release compatible with your **webtrees** version from the [webtrees-theme-rural GitHub repository](https://github.com/jon48/webtrees-theme-rural/releases).
2. Unzip the archive in `/modules_v4/`.
3. Check if the folder `myartjaub_ruraltheme` is now present in the `/modules_v4/` directory.
4. Open **webtrees** and the Rural Theme should now be available in the list of themes.

### Customising

It is possible to change the default webtrees logo in the header, and use a personal image instead.
I am using a customised header on my own website.
In all cases, please make sure that the personal image does not exceed ***150px*** in height.

#### Versions up to 1.7

Replace the image `/themes/rural/css-1.7.8/images/header.png` by a personal one.

#### Versions from 2.0

Replace the image `/modules_v4/myartjaub_ruraltheme/resources/images/header.png` by a personal one.

### Development

The Rural theme is based on the **[Bootstrap](https://getbootstrap.com/)** framework, and from version 2.0, uses **[Sass](https://sass-lang.com/)** to code the stylesheets (**Less** in prior versions). **Composer** and **npm** are required to generate the stylesheets.

If you wish to contribute, or create your own version of the Rural theme, here are some hints (valid for versions 2.0 and above).

#### Installation

Clone the repository to your local machine. You can either choose to create the directory directly in the `/modules_v4/` directory, or create it somewhere else, and symlink `/modules_v4/myartjaub_ruraltheme` to the newly created folder.

```shell
git clone https://github.com/jon48/webtrees-theme-rural.git target_directory
```

In the target directory, first install the Composer dependencies (do run in dev mode to be able to generate the stylesheets). The `fisharebest/webtrees` package is explicitly set to use sources to be able to generate the stylesheets, do not override that setting.

```shell
composer install
```

Then install the node dependencies (again, do run in dev mode to be able to generate the stylesheets)

```shell
npm install
```

#### Folder structure

```
|-- build/                        ->  Folder for intermediary build artefacts
|-- node_modules/                 ->  Node modules folder - do not alter
|-- resources/                    ->  Contains public resources, part of the final package 
|   |-- css/
|   |   |-- rural.min.js          ->  Rural Theme compiled stylesheet
|   |-- images/
|   |   |-- header.png            ->  Customisable header image
|   |-- views/
|       |-- footer.phtml          ->  HTML view to extend the footer with Rural images
|       |-- style.css.phtml       ->  Extra CSS stylesheet to load header image
|-- src/
|   |-- sass/                     ->  Sass code
|       |-- bootstrap/
|       |   |-- _pre-default.scss ->  Defines Bootstrap variable 
|       |   |-- config.scss       ->  Defines Bootstrap modules to generates
|       |-- resources/            ->  Resources used during Sass compilation
|       |   |-- images/           ->  Images are inlined in the compiled stylesheet
|       |-- rural/                ->  Rural Theme specific Sass code
|       |   |-- config.scss       ->  Rural Theme Sass entry point
|       |-- webtrees/
|       |   |-- config.scss       ->  Load specific mainstream webtrees CSS
|       |-- _variables.scss       ->  Defines global variables
|       |--theme.scss             ->  Sass entry point
|-- vendor/                       ->  Composer packages folder - do not alter
|-- module.php                    ->  Webtrees module entry point. Defines RuralTheme class.
|-- webpack.mix.config.js         ->  Global variables for Laravel Mix scripts
|-- webpack.mix.js                ->  Laravel Mix entry point
|-- webpack.mix.rural.js          ->  Lavarel Mix script to generate rural.min.css
|-- webpack.mix.webtrees.js       ->  Laravel Mix script to generate webtrees intermediary files
```

#### Build

Before building, make sure that the `fisharebest/webtrees` package is in the correct target **webtrees** version.

```shell
composer show fisharebest/webtrees
```

When ready to generate the stylesheets, run the following command. 

```shell
npm run production 
```

Let it compile until the `rural.min.css` has been generated in `/modules_v4/myartjaub_ruraltheme/resources/css` folder. 

NPM modes are:

- `production`: generates the `rural.min.css` file in production mode, and all required files. (Runs the `webtrees` and `rural` modes below, with the production flag)
- `development`: generates the `rural.min.css` file in development mode, and all required files. (Runs the `webtrees` and `rural` modes below)
- `webtrees`: generates intermediary CSS from the mainstream webtrees into the `/build/` folder. Those intermediary files are then injected in the main Rural theme CSS. Those files need to be generated once before running other modes, or after webtrees version has been updated via Composer. (Laravel Mix entry point: `webpack.mix.webtrees.js`)
- `rural`: generates the `rural.min.css` file in development mode (not minified). Requires the `webtrees` mode to have run once. (Laravel Mix entry point: `webpack.mix.rural.js`)
- `watch`: watches for changes in SASS code, and generate the `rural.min.css` file in development mode. Requires the `webtrees` mode to have run once. (Laravel Mix entry point: `webpack.mix.rural.js`)
- `eslint` / `eslint-fix`: Respectively checks and automatically format JavaScript code to enforce Semi-Standard code style
- `stylelint` / `stylelint-fix`: Respectively checks and automatically format CSS & SASS code to enforce StyleLint code style

### Issues / Security

Issues should be raised in the GitHub repository for **webtrees-theme-rural**.

A [security policy document](SECURITY.md) has been issued for this repository.

### Contacts

General questions on the standard **webtrees** software should be addressed to the
[official forum](https://www.webtrees.net/index.php/forum).

You can contact the author (Jonathan Jaubart) of the **webtrees-theme-rural** projects through his personal [GeneaJaubart website](https://genea.jaubart.com/wt/) (link at the bottom of the page), or raise in issue in GitHub.

