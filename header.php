<?php
// Header for Rural theme
//
// webtrees: Web based Family History software
// Copyright (C) 2014 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2009  PGV Development Team.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
//
// @author Jonathan Jaubart <dev@jaubart.com>

use WT\Auth;

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

global $WT_IMAGES;
// This theme uses the jQuery “colorbox” plugin to display images
$this
	->addExternalJavascript(WT_JQUERY_COLORBOX_URL)
	->addExternalJavascript(WT_JQUERY_WHEELZOOM_URL)
	->addInlineJavascript('
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
			current: "{current} '.WT_I18N::translate('of').' {total}",
			title: function(){
				var img_title = jQuery(this).data("title");
				return img_title;
			}
		});
	');

?>
<!DOCTYPE html>
<html <?php echo WT_I18N::html_markup(); ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- No mobile support yet  -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1">  -->
	<?php echo header_links($META_DESCRIPTION, $META_ROBOTS, $META_GENERATOR, $LINK_CANONICAL); ?>
	<title><?php echo WT_Filter::escapeHtml($title); ?></title>
	<link rel="icon" href="<?php echo WT_CSS_URL; ?>favicon.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="<?php echo WT_THEME_URL; ?>jquery-ui-1.11.2/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WT_CSS_URL; ?>style.css">
	<!--[if IE 8]>
	<script src="<?php echo WT_THEME_URL; ?>/modernizr-2.8.3/modernizr.min.js"></script>
	<![endif]-->

</head>
<body id="body">
<?php if ($view === 'simple') { ?>
	<div id="header_simple" > </div>
	<div id="main_content">
		<div class="top_center_box">
			<div class="top_center_box_left" ></div>
			<div class="top_center_box_right" ></div>
			<div class="top_center_box_center"></div>
		</div>
		<div class="content_box simpleview">
<?php } else { ?>
	<div id="main_content">
		<header>
			<div id="htopright">
				<div class="header_search">
					<form action="search.php" method="post" role="search">
						<input type="hidden" name="action" value="general">
						<input type="hidden" name="ged" value="<?php echo WT_GEDCOM; ?>">
						<input type="hidden" name="topsearch" value="yes">
						<input type="search" name="query" size="25" placeholder="<?php echo WT_I18N::translate('Search'); ?>">
						<input type="image" class="image" src="<?php echo $WT_IMAGES['search']; ?>" alt="<?php echo WT_I18N::translate('Search'); ?>" title="<?php echo WT_I18N::translate('Search'); ?>">
					</form>
				</div>
			</div>
			<div id="hcenterright">
				<ul class="makeMenu">
					<li>
					<?php 
					if (Auth::check()) {
						echo '<a href="edituser.php">', WT_I18N::translate('Logged in as '), ' ', WT_Filter::escapeHtml(Auth::user()->getRealName()), '</a></li> <li>', logout_link();
					} else {
						echo login_link();
					}
					?>
					</li>
				</ul>
				<h1><?php echo  WT_TREE_TITLE; ?></h1>
			</div>
			<div id="hbottomright">
				<div id="header-user-links">
					<ul class="makeMenu" role="menubar">
						<?php echo WT_MenuBar::getFavoritesMenu(); ?>
						<?php echo WT_MenuBar::getThemeMenu(); ?>
						<?php echo WT_MenuBar::getLanguageMenu(); ?>
					</ul>
				</div>
			</div>
			<?php 
			//Prepare menu bar
			$menu_items=array(
				WT_MenuBar::getGedcomMenu(),
				WT_MenuBar::getMyPageMenu(),
				WT_MenuBar::getChartsMenu(),
				WT_MenuBar::getListsMenu(),
				WT_MenuBar::getCalendarMenu(),
				WT_MenuBar::getReportsMenu(),
				WT_MenuBar::getSearchMenu(),
			);
			foreach (WT_MenuBar::getModuleMenus() as $menu) {
				$menu_items[]=$menu;
			}
			?>
		</header>
		<div class="top_center_box">
			<div class="top_center_box_left" ></div>
			<div class="top_center_box_right" ></div>
			<div class="top_center_box_center"></div>
		</div>
		<div class="content_box">
			<nav id="topMenu">
				<div class="topMenu_left"></div>
				<div class="topMenu_right"></div>
				<div class="topMenu_center">
					<table id="main-menu"  role="menubar"  align="center" >
						<tr>
						<?php  
							$nbMenus = count($menu_items);
							for ($i = 0; $i < $nbMenus -1 ; $i++) {
								$menu = $menu_items[$i];
								if ($menu) {?>
									<td valign="top"><ul class="main-menu-item"><?php echo $menu->getMenuAsList(); ?></ul></td>
								<?php }
							}
							$menu = $menu_items[$nbMenus - 1];
							if ($menu) { ?>
								<td class="topmenu_last" valign="top"><ul class="main-menu-item"><?php echo $menu->getMenuAsList(); ?></ul></td>
							<?php }
							unset($menu_items, $menu);
						?>
						</tr>
					</table>
				</div>
			</nav>
			
	<?php } ?>
	<?php echo WT_FlashMessages::getHtmlMessages(); ?>
	<main id="content">