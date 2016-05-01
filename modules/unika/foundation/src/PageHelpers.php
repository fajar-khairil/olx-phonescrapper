<?php

namespace Unika\Foundation;

class PageHelpers extends AbstractHelpers
{
	protected $styles = [];
	protected $scripts = [];

	public function styles()
	{
		return $this->styles;
	}

	public function scripts()
	{
		return $this->scripts;
	}

	public function addStyles(array $styles)
	{
		foreach ($styles as $style) {
			$this->styles[] = $style;
		}
	}

	public function addScripts(array $scripts)
	{
		foreach ($scripts as $script) {
			$this->scripts[] = $script;
		}
	}

	public function renderScripts()
	{
		$html = '';
		$base_uri = $this->container->base_uri;
		foreach ($this->scripts as $script) {
			
			$html .= '<script type="text/javascript" src="'.$base_uri.'/'.$script.'"></script>'.PHP_EOL;
		}

		return $html;
	}

	public function renderStyles()
	{
		$html = '';
		$base_uri = $this->container->base_uri;
		foreach ($this->styles as $style) {
			$html .= '<link rel="stylesheet" href="'.$base_uri.'/'.$style.'">'.PHP_EOL;
		}

		return $html;
	}
}