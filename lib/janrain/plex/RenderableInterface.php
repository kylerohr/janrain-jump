<?php
namespace janrain\plex;

interface RenderableInterface {
	public function getStartHeadJs();
	public function getSettingsHeadJs();
	public function getEndHeadJs();
	public function getCssHrefs();
	public function getCss();
	public function getHtml();
}