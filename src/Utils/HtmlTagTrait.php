<?php

namespace HBM\TwigAttributesBundle\Utils;

trait HtmlTagTrait
{
    protected static array $selfClosing = [
      'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
      'link', 'meta', 'param', 'source', 'track', 'wbr',
    ];

    protected ?string $tag;

    protected array $contents = [];

    /**
     * Set tag.
     */
    public function setTag(string $tag = null): static
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function tag(): static|string|null
    {
        if (func_num_args() === 0) {
            return $this->getTag();
        }

        $this->setTag(func_get_arg(0));

        return $this;
    }

    /**
     * Set content.
     */
    public function setContent(array|string|HtmlTag $contents): static
    {
        $this->contents = [];
        $this->addContent($contents);

        return $this;
    }

    public function addContent(mixed $contents): static
    {
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
     */
    public function getContent(): array
    {
        return $this->contents;
    }

    public function content(): array|static
    {
        if (func_num_args() === 0) {
            return $this->getContent();
        }

        $this->addContent(func_get_arg(0));

        return $this;
    }

    abstract protected function renderAttributes(): string;

    public function open(): string
    {
        if (in_array($this->getTag(), self::$selfClosing, true)) {
            return '<' . $this->getTag() . ' ' . $this->renderAttributes() . ' />';
        }

        return '<' . $this->getTag() . ' ' . $this->renderAttributes() . '>';
    }

    public function close(): ?string
    {
        if (in_array($this->getTag(), self::$selfClosing, true)) {
            return null;
        }

        return '</' . $this->getTag() . '>';
    }

    public function __toString(): string
    {
        if (in_array($this->getTag(), self::$selfClosing, true)) {
            return '<' . $this->getTag() . ' ' . $this->renderAttributes() . ' />';
        }

        return '<' . $this->getTag() . ' ' . $this->renderAttributes() . '>' . implode('', $this->getContent()) . '</' . $this->getTag() . '>';
    }
}
