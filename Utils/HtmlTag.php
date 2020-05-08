<?php

namespace HBM\TwigAttributesBundle\Utils;

use Exception;

class HtmlTag extends HtmlAttributes {

  protected static $selfClosing = [
    'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
    'link', 'meta', 'param', 'source', 'track', 'wbr'
  ];

  /**
   * @var string|null
   */
  private $tag;

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

  /****************************************************************************/

  /**
   * Set tag.
   *
   * @param string $tag
   *
   * @return self
   */
  public function setTag(string $tag = NULL) : self {
    $this->tag = $tag;

    return $this;
  }

  /**
   * Get tag.
   *
   * @return string|null
   */
  public function getTag() : ?string {
    return $this->tag;
  }

  /**
   * @return $this|string|null
   */
  public function tag() {
    if (func_num_args() === 0) {
      return $this->getTag();
    }

    $this->setTag(func_get_arg(0));

    return $this;
  }

  /****************************************************************************/

  /**
   * @return string
   */
  public function open() : string {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return '<'.$this->getTag().' '.parent::__toString().' />';
    } else {
      return '<'.$this->getTag().' '.parent::__toString().'>';
    }
  }

  /**
   * @return string|null
   */
  public function close() : ?string {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return NULL;
    } else {
      return '</'.$this->getTag().'>';
    }
  }

  /**
   * @return string
   */
  public function __toString() {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return '<'.$this->getTag().' '.parent::__toString().' />';
    } else {
      return '<'.$this->getTag().' '.parent::__toString().'></'.$this->getTag().'>';
    }
  }

}
