<?php

namespace HBM\TwigAttributesBundle\Twig;

use HBM\TwigAttributesBundle\Utils\HtmlAttributes;
use HBM\TwigAttributesBundle\Utils\HtmlTag;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class AttributesExtension extends AbstractExtension
{
    public function __construct(private ?TranslatorInterface $translator = null)
    {
    }

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

    public function getFilters(): array
    {
        return [
          new TwigFilter('transTag', $this->transTag(...)),
          new TwigFilter('transAttr', $this->transAttr(...)),
        ];
    }

    /* FILTERS */

    /**
     * Translates a html tag. Translates the title attribute by default. Add more attribute keys to translate them.
     */
    public function transTag(HtmlTag $tag = null, array $attributeArguments = ['title' => []], string $domain = null, string $locale = null): ?HtmlTag
    {
        if ($tag === null) {
            return null;
        }

        $translatedArguments = $this->transAttr($tag->getAttributesObject(), $attributeArguments, $domain, $locale)?->toArray();

        return (clone $tag)->setAttributes($translatedArguments ?? []);
    }

    /**
     * Translates a html tag. Translates the title attribute by default. Add more attribute keys to translate them.
     */
    public function transAttr(HtmlAttributes $attributes = null, array $attributeArguments = ['title' => []], string $domain = null, string $locale = null): ?HtmlAttributes
    {
        if ($attributes === null) {
            return null;
        }

        $clonedAttributes = clone $attributes;
        foreach ($attributeArguments as $key => $arguments) {
            $value = $clonedAttributes->get($key);
            $clonedAttributes->set($key, $this->translator->trans($value, $arguments, $domain, $locale));
        }

        return $clonedAttributes;
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
