<?php
namespace janrain\plex;

interface PlexInterface {
	public function getStartHeadJs();
	public function getSettingsHeadJs();
	public function getEndHeadJs();
	public function getCssHrefs();
	public function getCss();
	public function getWidgetBody();
}