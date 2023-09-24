<?php

namespace HBM\TwigAttributesBundle\Twig;

use HBM\TwigAttributesBundle\Utils\HtmlAttributes;
use HBM\TwigAttributesBundle\Utils\HtmlTag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class AttributesExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
          new TwigTest('attributes', $this->isAttributes(...)),
        ];
    }

    public function getFunctions(): array
    {
        return [
          new TwigFunction('attributes', $this->attributes(...)),
          new TwigFunction('tag', $this->tag(...)),
        ];
    }

    /* FUNCTIONS */

    /**
     * Creates an html attribute object.
     */
    public function attributes(HtmlAttributes|array $attributes = null): HtmlAttributes
    {
        return new HtmlAttributes($attributes);
    }

    /**
     * Creates an html tag object.
     */
    public function tag(string $tag = null, HtmlAttributes|array $attributes = null): HtmlTag
    {
        return new HtmlTag($tag, $attributes);
    }

    /* TESTS */

    public function isAttributes($var): bool
    {
        return $var instanceof HtmlAttributes;
    }
}
