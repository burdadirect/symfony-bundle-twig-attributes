<?php

namespace HBM\TwigAttributesBundle\Utils;

class HtmlTag extends HtmlAttributes
{
    use HtmlTagTrait;

    /**
     * HtmlTag constructor.
     */
    public function __construct(string $tag = null, HtmlAttributes|array|string $attributes = null, mixed $onlyIfNotEmpty = false)
    {
        parent::__construct($attributes, $onlyIfNotEmpty);

        $this->tag = $tag;
    }

    /**
     * Returns a copy of the html attributes.
     */
    public function copy(): HtmlTag
    {
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
     * @return null|HtmlAttributes|HtmlTag|mixed|string[]
     */
    public function attr(): mixed
    {
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

    protected function renderAttributes(): string
    {
        return parent::__toString();
    }
}
