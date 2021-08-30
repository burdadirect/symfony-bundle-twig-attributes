<?php

namespace HBM\TwigAttributesBundle\Utils;

trait HtmlTagTrait {

  protected static $selfClosing = [
    'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
    'link', 'meta', 'param', 'source', 'track', 'wbr'
  ];

  /**
   * @var string|null
   */
  protected $tag;

  /**
   * Set tag.
   *
   * @param string|null $tag
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
  abstract protected function renderAttributes() : string;

  /**
   * @return string
   */
  public function open() : string {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return '<'.$this->getTag().' '.$this->renderAttributes().' />';
    }
    return '<'.$this->getTag().' '.$this->renderAttributes().'>';
  }

  /**
   * @return string|null
   */
  public function close() : ?string {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return NULL;
    }
    return '</'.$this->getTag().'>';
  }

  /**
   * @return string
   */
  public function __toString() {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return '<'.$this->getTag().' '.$this->renderAttributes().' />';
    }
    return '<'.$this->getTag().' '.$this->renderAttributes().'></'.$this->getTag().'>';
  }

}
