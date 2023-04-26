<?php

namespace HBM\TwigAttributesBundle\Utils;

trait HtmlTagTrait {

  protected static array $selfClosing = [
    'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
    'link', 'meta', 'param', 'source', 'track', 'wbr'
  ];

  protected ?string $tag;

  protected array $contents = [];

  /**
   * Set tag.
   *
   * @param string|null $tag
   *
   * @return static
   */
  public function setTag(string $tag = NULL) : static {
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
   * @return static|string|null
   */
  public function tag(): static|string|null {
    if (func_num_args() === 0) {
      return $this->getTag();
    }

    $this->setTag(func_get_arg(0));

    return $this;
  }

  /****************************************************************************/

  /**
   * Set content.
   *
   * @param array|string|HtmlTag $contents
   *
   * @return static
   */
  public function setContent(array|string|HtmlTag $contents) : static {
    $this->contents = [];
    $this->addContent($contents);

    return $this;
  }

  /**
   * @param mixed $contents
   *
   * @return static
   */
  public function addContent(mixed $contents) : static {
    if (!is_array($contents)) {
      $contents = [$contents];
    }
    foreach ($contents as $content) {
      if (($content !== null) && ($content !== '')) {
        $this->contents[] = $content;
      }
    }

    return $this;
  }

  /**
   * Get contents.
   *
   * @return array
   */
  public function getContent() : array {
    return $this->contents;
  }

  /**
   * @return static|array
   */
  public function content(): array|static {
    if (func_num_args() === 0) {
      return $this->getContent();
    }

    $this->addContent(func_get_arg(0));

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
  public function __toString(): string {
    if (in_array($this->getTag(), self::$selfClosing, TRUE)) {
      return '<'.$this->getTag().' '.$this->renderAttributes().' />';
    }
    return '<'.$this->getTag().' '.$this->renderAttributes().'>'.implode('', $this->getContent()).'</'.$this->getTag().'>';
  }

}
