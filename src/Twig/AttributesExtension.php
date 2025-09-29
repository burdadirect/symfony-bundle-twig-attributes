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

    public const REGEX_TAG   = '([a-z]*?)';
    public const REGEX_ID    = '(?:#([\w-]+))';
    public const REGEX_CLASS = '(?:\.([\w-]+))';
    public const REGEX_ATTR  = '(?:\[(?:([\w-]+)(?:=?(?:"(.*?)"))?)?])';

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
          new TwigFunction('tagParse', $this->tagParse(...)),
        ];
    }

    public function getFilters(): array
    {
        return [
          new TwigFilter('tag', $this->tagFilter(...), ['is_safe' => ['html']]),
          new TwigFilter('tagNotEmpty', $this->tagNotEmptyFilter(...), ['is_safe' => ['html']]),

          new TwigFilter('transTag', $this->transTag(...)),
          new TwigFilter('transAttr', $this->transAttr(...)),
        ];
    }

    /* FILTERS */

    public function tagFilter(HtmlTag $tag = null): ?string {
        if ($tag === null) {
            return null;
        }

        return (string) $tag;
    }

    public function tagNotEmptyFilter(HtmlTag $tag = null): ?string {
        if ($tag === null) {
            return null;
        }
        if (count($tag->getContent()) === 0) {
            return null;
        }

        return (string) $tag;
    }

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

    public function tagParse(string $tagString = null, HtmlAttributes|array $attributes = null): HtmlTag
    {
      if (!$attributes instanceof HtmlAttributes) {
        $attributes = new HtmlAttributes($attributes);
      }

      $tagElement = $tagString;
      if (preg_match('/^'.self::REGEX_TAG.'{1}('.self::REGEX_ID.'?'.self::REGEX_CLASS.'*'.self::REGEX_ATTR.'*)?$/', $tagString, $matches)) {
        $tagElement = $matches[1];
        $this->tagParseAttributes($matches[2], $attributes);
      }

      return new HtmlTag($tagElement, $attributes);
    }

    private function tagParseAttributes(string $string, HtmlAttributes $attributes): self {

        if (preg_match('/^'.self::REGEX_ID.'(.*)/', $string, $matches)) {
            $attributes->id($matches[1]);
            return $this->tagParseAttributes($matches[2], $attributes);

        }
        if (preg_match('/^'.self::REGEX_CLASS.'(.*)/', $string, $matches)) {
            $attributes->class($matches[1]);
            return $this->tagParseAttributes($matches[2], $attributes);
        }
        if (preg_match('/^'.self::REGEX_ATTR.'(.*)/', $string, $matches)) {
            $attributes->set($matches[1], $matches[2]);
            return $this->tagParseAttributes($matches[3], $attributes);
        }

        return $this;
    }

    /* TESTS */

    public function isAttributes($var): bool
    {
        return $var instanceof HtmlAttributes;
    }
}
