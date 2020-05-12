<?php

namespace HBM\TwigAttributesBundle\Utils;

class HtmlTag extends HtmlAttributes {

  use HtmlTagTrait;

  protected static $selfClosing = [
    'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
    'link', 'meta', 'param', 'source', 'track', 'wbr'
  ];

  /**
   * HtmlTag constructor.
   *
   * @param string|null $tag
   * @param mixed $attributes
   * @param bool $onlyIfNotEmpty
   */
  public function __construct(string $tag = NULL, $attributes = NULL, $onlyIfNotEmpty = FALSE) {
    parent::__construct($attributes, $onlyIfNotEmpty);

    $this->tag = $tag;
  }

  /**
   * Returns a copy of the html attributes.
   *
   * @return self
   */
  public function copy() {
    $copy = new HtmlTag();
    $copy->setTag($this->getTag());
    $copy->setClasses($this->getClasses());
    $copy->setAttributes($this->getAttributes());

    return $copy;
  }

  /**
   * @return string
   */
  protected function renderAttributes() : string {
    return parent::__toString();
  }

}
