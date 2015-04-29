=== Mega Taxonomy Menu ===
Contributors: dalewpdevph
Tags: dropdown menu, hover, icons, mega menu, megamenu, menu, menu icons, menu style, navigation, responsive, responsive menu, theme editor
Requires at least: 3.5
Tested up to: 4.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Very fast articles mega menu


== Description ==

Demo: http://softanalyzer.com/dale/mega-taxonomy-menu/
Video: https://www.youtube.com/watch?v=8yri8H3VxTQ
<h3>Features</h3>

<ul>
<li>Supports HTML5 local storage which makes the menu very fast to load.</li>

<li>Fully Responsive menu.</li>

<li>Support custom taxonomy and post type.</li>

<li>Manageable menu background colors and text through back end.</li>

<li>Able to select WordPress menu to convert it to Mega Taxonomy Menu.</li>

<li>Allow to input width and height of the menu through backend settings.</li>

<li>Choose image size and layout of the articles or posts in the menu.</li>

<li>Choose how many articles to display in the menu through backend settings.</li>

<li>Clearing cache button at the backend settings.</li>

<li>Files include Api calls to Google Map API Web Services.</li>
</ul>

== Installation ==

1. On setting up the plugin, Make sure you copied the plugin in the plugin directory then activate it.
2. Go to the Mega Taxonomy Menu Tab -> Select a menu you have created in Appearance -> Menu then Save Options.
3. After open up your activated Theme "header.php" file.
4. Replace the old header with this function: <?php mega_taxonomy_menu(); ?>
OR wrap it with "if(function_exists('mega_taxonomy_menu'))" like this
<?php
if(function_exists("mega_taxonomy_menu")) { mega_taxonomy_menu(); }
?>
So that the site will not break if you deactivated the plugin.
5. Save the file and go the front end. 
6. Hit refresh You will now see the Mega Taxonomy Menu.

== Screenshots ==
1. Frond end display
2. Column links
3. Backend options
4. Mobile view