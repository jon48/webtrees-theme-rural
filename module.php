<?php

/**
 * Rural Theme
 *
 * webtrees-MyArtJaub
 * Copyright (C) 2009-2024 Jonathan Jaubart
 *
 * Based on webtrees: online genealogy
 * Copyright (C) 2020 webtrees development team
 *
 * This file is part of webtrees-MyArtJaub
 *
 * webtrees-MyArtJaub is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * webtrees-MyArtJaub is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with webtrees-MyArtJaub. If not, see <https://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace MyArtJaub\Webtrees\Module;

use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\View;
use Fisharebest\Webtrees\Webtrees;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleFooterInterface;
use Fisharebest\Webtrees\Module\ModuleFooterTrait;
use Fisharebest\Webtrees\Module\ModuleThemeInterface;
use Fisharebest\Webtrees\Module\ModuleThemeTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RuralTheme - Main class for Rural Theme.
 */
class RuralTheme extends AbstractModule implements ModuleCustomInterface, ModuleFooterInterface, ModuleThemeInterface
{
    use ModuleCustomTrait;
    use ModuleFooterTrait;
    use ModuleThemeTrait;

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\AbstractModule::title()
     */
    public function title(): string
    {
        return I18N::translate('Rural');
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\AbstractModule::boot()
     */
    public function boot(): void
    {
        // Register a namespace for our views.
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\AbstractModule::resourcesFolder()
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }


    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\AbstractModule::stylesheets()
     */
    public function stylesheets(): array
    {
        $header_path = $this->resourcesFolder() . $this->getHeaderImagePath();
        $hash = hash('xxh64', $this->customModuleVersion() . '#' . filemtime($header_path));

        return [
            $this->assetUrl('css/rural.min.css'),
            route('module', ['module' => $this->name(), 'action' => 'CustomCss', 'h' => $hash])
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleAuthorName()
     */
    public function customModuleAuthorName(): string
    {
        return 'Jonathan Jaubart';
    }


    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleVersion()
     */
    public function customModuleVersion(): string
    {
        return '2.2.0-v.1';
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleLatestVersionUrl()
     */
    public function customModuleLatestVersionUrl(): string
    {
        return 'https://apps.jaubart.com/myartjaub/webtrees-modules/api/module/myartjaub_ruraltheme/latest?' .
            http_build_query([
                'm' => $this->customModuleVersion(),
                'w' => Webtrees::VERSION,
                'p' => PHP_VERSION,
                'o' => DIRECTORY_SEPARATOR === '/' ? 'u' : 'w',
                's' => Site::getPreference('SITE_UUID')
            ]);
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleSupportUrl()
     */
    public function customModuleSupportUrl(): string
    {
        return 'https://github.com/jon48/webtrees-theme-rural';
    }

    /**
     * Request handler for CustomCss
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getCustomCssAction(ServerRequestInterface $request): ResponseInterface
    {
        $header_file = $this->getHeaderImagePath();

        $content = view(
            $this->name() . '::style.css',
            [
                'header_path'   => Registry::container()->get(ModuleThemeInterface::class)->assetUrl($header_file),
                'header_height' => $this->getPngHeight($this->resourcesFolder() . $header_file)
            ]
        );
        return response($content)
            ->withHeader('Cache-Control', 'public,max-age=31536000')
            ->withHeader('Content-Type', 'text/css')
        ;
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleFooterInterface::defaultFooterOrder()
     */
    public function defaultFooterOrder(): int
    {
        return 99;
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleFooterInterface::getFooter()
     */
    public function getFooter(\Psr\Http\Message\ServerRequestInterface $request): string
    {
        if (Registry::container()->get(ModuleThemeInterface::class)->name() === $this->name()) {
            return view($this->name() . '::footer');
        }
        return '';
    }

    /**
     * Returns the path of the header file to use
     *
     * @return string
     */
    private function getHeaderImagePath(): string
    {
        return file_exists($this->resourcesFolder() . 'images/header.png') ?
            'images/header.png' :
            'images/header.default.png';
    }

    /**
     * Extracts the height of a PNG image.
     * This is a faster implementation than going through the getimagesize function.
     *
     * @param string $path Path of the PNG image
     * @return int Height
     */
    private function getPngHeight(string $path): int
    {
        $handle = fopen($path, 'r');
        if ($handle !== false) {
            $contents = fread($handle, 25);
            fclose($handle);
            $height = $contents === false ? 0 : (unpack('N*', substr($contents, 20, 4))[1] ?? null);
            return is_int($height) ? $height : 0;
        }
        return 0;
    }
}

return new RuralTheme();
