<?php
namespace janrain\plex;

interface RenderableInterface {
	/**
	 * Start the JS Output
	 * 
	 * @return string
	 *   The string representing the start of JUMP javascript to be placed within HTML Head tag.
	 *   Does not contain opening <script>.  If this Renderable doesn't require JS, this should return empty string.
	 */
	public function getStartHeadJs();

	/**
	 * Get the javascript settings for this Renderable JUMP object
	 *
	 * @return string
	 *   A block of settings in the form of "janrain.settings.package.option = 'value'\n".  If no settings are required
	 *   for this renderable, return an empty string.
	 */
	public function getSettingsHeadJs();

	/**
	 * Get the closing javascript content and load.js src.
	 *
	 * @return string
	 *   The closing block of head javascript.  Does not include a </script>.  If no JS required, this returns empty string.
	 */
	public function getEndHeadJs();

	/**
	 * Get the hrefs of the external CSS this renderable requires.
	 * 
	 * @return Array|Traversible
	 *   Returns a Traversible data structure such that can be fed to foreach with each value being a valid href string.
	 *   If this renderable does not depend on any external CSS, an empty array is returned.
	 *   Does not return link tags, only the href values.
	 */
	public function getCssHrefs();

	/**
	 * Get the inline style needed for this renderable.
	 *
	 * @return string
	 *   The inline CSS rules for this renderable.  Does not return opening or closing style tags.
	 */
	public function getCss();

	/**
	 * Get the body content of this Renderable
	 *
	 * @return string
	 *   Returns the html markup for this Renderable.
	 */
	public function getHtml();
}