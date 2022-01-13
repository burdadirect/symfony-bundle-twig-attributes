<?php

namespace HBM\TwigAttributesBundle\Utils;

class HtmlTag extends HtmlAttributes {

  use HtmlTagTrait;

  /**
   * HtmlTag constructor.
   *
   * @param string|null $tag
   * @param mixed $attributes
   * @param bool|mixed $onlyIfNotEmpty
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
  public function copy(): HtmlTag {
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
