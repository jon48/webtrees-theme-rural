<?php
/**
 * webtrees-MyArtJaub
 * Copyright (C) 2015 Jonathan Jaubart
 * 
 * Based on webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
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
 * along with webtrees-MyArtJaub. If not, see <http://www.gnu.org/licenses/>.
 */

namespace MyArtJaub\Webtrees\Theme;

use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\FlashMessages;
use Fisharebest\Webtrees\Menu;

/**
 * Class RuralTheme - Main class for Rural Theme.
 */
class RuralTheme extends \Fisharebest\Webtrees\Theme\AbstractTheme implements
	\Fisharebest\Webtrees\Theme\ThemeInterface
{
	
	/**
	 * {@inheritdoc}
	 */
	public function themeId() {
		return 'rural';
	}

	/**
	 * {@inheritdoc}
	 */
	public function themeName() {
		return /* I18N: Name of the Rural theme. */ I18N::translate('Rural');
	}
	
	/** {@inheritdoc} */
	public function assetUrl() {
		return 'themes/rural/css-1.7.3/';
	}
	
	/** {@inheritdoc} */
	protected function favicon() {
		return '<link rel="icon" href="' . $this->assetUrl() . 'favicon.png" type="image/png">';
	}
	
	/** {@inheritdoc} */
	protected function stylesheets() {		
		$stylesheets = parent::stylesheets();
		
		$stylesheets[] = 'themes/rural/jquery-ui-1.11.2/jquery-ui.css';
		$stylesheets[] = $this->assetUrl() . 'style-'.I18N::direction().'.css';
		
		return $stylesheets;
	}
	
	/** {@inheritdoc} */
	public function hookFooterExtraJavascript() {
		return
		'<script src="' . WT_BOOTSTRAP_JS_URL . '"></script>' .
		'<script src="' . WT_JQUERY_COLORBOX_URL . '"></script>' .
		'<script src="' . WT_JQUERY_WHEELZOOM_URL . '"></script>' .
		'<script>
		activate_colorbox();
	
		jQuery("body").on("click", "a.gallery", function(event) {
			// Add colorbox to pdf-files
			jQuery("a[type^=application].gallery").colorbox({
				innerWidth: "75%",
				innerHeight:"75%",
				rel:        "gallery",
				iframe:     true,
				photo:      false,
				slideshow:     true,
				slideshowAuto: false,
				title:		function(){
					var url = jQuery(this).attr("href");
					var img_title = jQuery(this).data("title");
					return "<a href=\"" + url + "\" target=\"_blank\">" + img_title + "</a>";
				}
			});
		});
		jQuery.extend(jQuery.colorbox.settings, {
			initialWidth: "20%", initialHeight: "20%",
			slideshowStart: "<div id=\"cboxSlideshowStart\">&nbsp;</div>",
			slideshowStop: "<div id=\"cboxSlideshowStop\">&nbsp;</div>",
			transition: "none",
			current: "{current} '.I18N::translate('of').' {total}",
			title: function(){
				var img_title = jQuery(this).data("title");
				return img_title;
			}
		});
		</script>';
	}
	
	/** {@inheritdoc} */
	public function parameter($parameter_name) {
		$parameters = array(
				'chart-background-f'             => 'e9daf1',
				'chart-background-m'             => 'b1cff0',
				'distribution-chart-high-values' => '9ca3d4',
				'distribution-chart-low-values'  => 'e5e6ef'
		);
	
		if (array_key_exists($parameter_name, $parameters)) {
			return $parameters[$parameter_name];
		} else {
			return parent::parameter($parameter_name);
		}
	}	
	
	/** {@inheritdoc} */
	public function bodyHeader() {
		return
		'<body id="body">' .
			'<div class="row">'.
				'<div class="main-col-layout">'.
					'<header>' .
						$this->headerContent() .
					'</header>' .
					'<div class="content_box">' .
						$this->primaryMenuContainer($this->primaryMenu()) .
						'<main id="content" role="main">' .
						$this->flashMessagesContainer(FlashMessages::getMessages());
	}
	
	
	/** {@inheritdoc} */
	public function bodyHeaderPopupWindow() {
		return '<body id="body" class="popupwindow">' .
			'<div class="row">'.
				'<div class="main-col-layout">'.
					'<div class="content_box">' .
                        '<main id="content" role="main">' .
                        $this->flashMessagesContainer(FlashMessages::getMessages());
	}
	
	/** {@inheritdoc} */
	public function footerContainer() {
		return 
					'</main>'.
					'<footer>' .
						'<div class="footer_left">'.
							'<div class="footer_right">'.
								'<div class="footer_center">'.
								$this->footerContent() .
								'</div>' . 	// -- .footer_center
							'</div>' .	// -- .footer_right
						'</div>' .	// -- .footer_left
					'</footer>' .
				'</div>'.	// -- #content_box
			'</div>'. 	// -- .main-col-layout
		'</div>';	// -- .row	
	}
	
	/** {@inheritdoc} */
	public function footerContainerPopupWindow() {
		return        '</main>'.
				'</div>'.	// -- #content_box
			'</div>'. 	// -- .main-col-layout
		'</div>';	// -- .row		
		;
	}

	/** {@inheritdoc} */
	protected function headerContent() {
		$secondary_menu = $this->secondaryMenu();
		$login_menu = array_filter(array(
			$this->menuLogin(),
			$this->menuLogout(),
		));
		
		return 
			'<div id="header-content-lrg">'.
				'<div class="header-row"><div id="header-top">'.
					'<div id="header-user-links" class="">'.
						$this->secondaryMenuContainer($secondary_menu).
					'</div>'.
				'</div></div>'.
				'<div class="header-row"><div id="header-middle">'.
					'<ul>' . implode(' | ', array_map(function (Menu $menu) { return $menu->getMenuAsList(); }, $login_menu)) . '</ul>'.
					//$this->secondaryMenuContainer($login_menu).
					$this->formatTreeTitle() .
				'</div></div>'.
				'<div class="header-row"><div id="header-bottom">'.
					$this->formQuickSearch().
				'</div></div>'.
			'</div>'.     // --  #header-content-lrg
            '<div id="header-content-xs">'.
                '<nav class="secondary-menu-nav-xs-holder">'.
                    '<div class="secondary-menu-nav-xs-header">'.
                        '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#secondary-menu-nav-xs" aria-expanded="false">'.
                            '<span class="icon-bar"></span>' .
                            '<span class="icon-bar"></span>' .
                            '<span class="icon-bar"></span>' .
                        '</button>' .
                        '<a class="navbar-brand secondary-menu-nav-xs-brand" href="#">'.$this->formatTreeTitle() .'</a>'.
                    '</div>'.
                    '<div id="secondary-menu-nav-xs">'.
                        '<ul class="nav nav-pills secondary-menu">' . 
                            $this->secondaryMenuContent(array_merge($login_menu, $secondary_menu)) . 
                        '</ul>' .
                    '</div>' .
                '</nav>' .
            '</div>'
        ;
	}
	
	/** {@inheritdoc} */
	protected function secondaryMenu() {
		return array_filter(array(
				$this->menuPendingChanges(),
				$this->menuMyPages(),
				$this->menuFavorites(),
				$this->menuThemes(),
				$this->menuLanguages()
		));
	}
	
    /** {@inheritdoc} */
    protected function primaryMenuContainer(array $menus) {
		return '<nav class="primary-menu-nav-holder">'.
            '<div class="primary-menu-nav-header">'.
                '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#primary-menu-nav" aria-expanded="false">'.
                    '<span class="icon-bar"></span>' .
                    '<span class="icon-bar"></span>' .
                    '<span class="icon-bar"></span>' .
                '</button>' .
                '<a class="navbar-brand primary-menu-brand" href="#">'.I18N::translate('Menu').'</a>'.
            '</div>'.
            '<div id="primary-menu-nav">'.
                '<ul class="primary-menu">' . 
                    $this->primaryMenuContent($menus) . 
                '</ul>' .
            '</div>' .
        '</nav>';
	}
    
    /** {@inheritdoc} */
	protected function primaryMenuContent(array $menus) {
		return implode('', array_map(function (Menu $menu) { return $menu->bootstrap(); }, $menus));
	}
	
    /** {@inheritdoc} */
	protected function secondaryMenuContent(array $menus) {
		return implode('', array_map(function (Menu $menu) { return $menu->bootstrap(); }, $menus));
	}


}

return new RuralTheme();
