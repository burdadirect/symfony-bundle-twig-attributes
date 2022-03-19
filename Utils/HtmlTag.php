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
   * Should be placed here to not interfere with the "attr" method when the trait
   * is used in BootstramItem.
   *
   * @return HtmlTag|HtmlAttributes|mixed|string[]|null
   */
  public function attr() {
    if (func_num_args() === 0) {
      return $this->getAttributesObject();
    }
    if (func_num_args() === 1) {
      return $this->get(func_get_arg(0));
    }
    if (func_num_args() === 2) {
      return $this->set(func_get_arg(0), func_get_arg(1));
    }

    return $this;
  }


  /**
   * @return string
   */
  protected function renderAttributes() : string {
    return parent::__toString();
  }

}
