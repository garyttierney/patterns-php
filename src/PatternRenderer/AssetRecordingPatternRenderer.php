<?php

namespace eLife\Patterns\PatternRenderer;

use ArrayObject;
use function eLife\Patterns\flatten;
use eLife\Patterns\HasAssets;
use eLife\Patterns\PatternRenderer;
use function eLife\Patterns\sanitise_traversable;
use eLife\Patterns\ViewModel;
use Traversable;

final class AssetRecordingPatternRenderer implements PatternRenderer, HasAssets
{
    private $patternRenderer;
    private $styleSheets;
    private $inlineStyleSheets;
    private $javaScripts;
    private $inlineJavaScripts;

    public function __construct(PatternRenderer $patternRenderer)
    {
        $this->patternRenderer = $patternRenderer;
        $this->styleSheets = new ArrayObject();
        $this->inlineStyleSheets = new ArrayObject();
        $this->javaScripts = new ArrayObject();
        $this->inlineJavaScripts = new ArrayObject();
    }

    public function render(ViewModel $viewModel) : string
    {
        foreach (sanitise_traversable($viewModel->getStyleSheets()) as $styleSheet) {
            if (false !== $this->contains($this->styleSheets, $styleSheet)) {
                continue;
            }
            $this->styleSheets[] = $styleSheet;
        };

        foreach (flatten($viewModel->getInlineStyleSheets()) as $styleSheet) {
            $this->inlineStyleSheets[] = $styleSheet;
        };

        foreach (sanitise_traversable($viewModel->getJavaScripts()) as $javaScript) {
            if (false !== $this->contains($this->javaScripts, $javaScript)) {
                continue;
            }
            $this->javaScripts[] = $javaScript;
        };

        foreach (flatten($viewModel->getInlineJavaScripts()) as $javaScript) {
            $this->inlineJavaScripts[] = $javaScript;
        };

        return $this->patternRenderer->render($viewModel);
    }

    public function getStyleSheets() : Traversable
    {
        return $this->styleSheets;
    }

    public function getInlineStyleSheets() : Traversable
    {
        return $this->inlineStyleSheets;
    }

    public function getJavaScripts() : Traversable
    {
        return $this->javaScripts;
    }

    public function getInlineJavaScripts() : Traversable
    {
        return $this->inlineJavaScripts;
    }

    private function contains(ArrayObject $array, $item, bool $strict = false) : bool
    {
        return false !== array_search($item, $array->getArrayCopy(), $strict);
    }
}
