<?php

namespace HBM\TwigAttributesBundle\Twig;

use HBM\TwigAttributesBundle\Utils\HtmlAttributes;
use HBM\TwigAttributesBundle\Utils\HtmlTag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class AttributesExtension extends AbstractExtension {

  public function getTests() : array {
    return [
      new TwigTest('attributes', [$this, 'isAttributes']),
    ];
  }

  public function getFunctions() : array {
    return [
      new TwigFunction('attributes', [$this, 'attributes']),
      new TwigFunction('tag', [$this, 'tag']),
    ];
  }

  /****************************************************************************/
  /* FUNCTIONS                                                                */
  /****************************************************************************/

  /**
   * Creates an html attribute object.
   *
   * @param HtmlAttributes|array|null $attributes
   *
   * @return HtmlAttributes
   */
  public function attributes(HtmlAttributes|array|null $attributes = NULL) : HtmlAttributes {
    return new HtmlAttributes($attributes);
  }

  /**
   * Creates an html tag object.
   *
   * @param string|null $tag
   * @param HtmlAttributes|array|null $attributes
   *
   * @return HtmlTag
   */
  public function tag(?string $tag = NULL, HtmlAttributes|array|null $attributes = NULL) : HtmlTag {
    return new HtmlTag($tag, $attributes);
  }

  /****************************************************************************/
  /* TESTS                                                                    */
  /****************************************************************************/

  /**
   * @param $var
   * @return bool
   */
  public function isAttributes($var) : bool {
    return $var instanceof HtmlAttributes;
  }

}
