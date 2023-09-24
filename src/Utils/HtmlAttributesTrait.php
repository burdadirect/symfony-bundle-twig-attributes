<?php

namespace HBM\TwigAttributesBundle\Utils;

trait HtmlAttributesTrait
{
    public function getAttributesObject(): HtmlAttributes
    {
        return $this;
    }

    public function class(): array|static
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->getClasses();
        }

        if ((func_num_args() === 2) && is_bool(func_get_arg(1))) {
            if (func_get_arg(1) === true) {
                $this->getAttributesObject()->addClasses(func_get_arg(0));
            }
        } elseif ((func_num_args() === 3) && is_bool(func_get_arg(1))) {
            if (func_get_arg(1) === true) {
                $this->getAttributesObject()->addClasses(func_get_arg(0));
            } else {
                $this->getAttributesObject()->addClasses(func_get_arg(2));
            }
        } else {
            foreach (func_get_args() as $classes) {
                $this->getAttributesObject()->addClasses($classes);
            }
        }

        return $this;
    }

    public function href(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('href');
        }

        if (func_num_args() === 1) {
            $this->getAttributesObject()->set('href', func_get_arg(0));
        } elseif (func_num_args() === 2) {
            $this->getAttributesObject()->set('href', func_get_arg(0));
            $this->getAttributesObject()->set('target', func_get_arg(1));
        }

        return $this;
    }

    public function src(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('src');
        }

        if (func_num_args() === 1) {
            $this->getAttributesObject()->set('src', func_get_arg(0));
        } elseif (func_num_args() === 2) {
            $this->getAttributesObject()->set('src', func_get_arg(0));
            $this->getAttributesObject()->set('srcset', func_get_arg(0) . ' 1x, ' . func_get_arg(1) . ' 2x');
        }

        return $this;
    }

    public function title(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('title');
        }

        if ((func_num_args() === 2) && func_get_arg(1)) {
            $this->getAttributesObject()->setIfEmpty('title', func_get_arg(0));
        } else {
            $this->getAttributesObject()->set('title', func_get_arg(0));
        }

        return $this;
    }

    public function alt(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('alt');
        }

        $this->getAttributesObject()->set('alt', func_get_arg(0));

        return $this;
    }

    public function altTitle(): static
    {
        $this->getAttributesObject()->set('alt', func_get_arg(0));
        $this->getAttributesObject()->set('title', func_get_arg(0));

        return $this;
    }

    public function target(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('target');
        }

        $this->getAttributesObject()->set('target', func_get_arg(0));

        return $this;
    }

    public function onclick(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('onclick');
        }

        $this->getAttributesObject()->set('onclick', func_get_arg(0));

        return $this;
    }

    public function id(): string|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('id');
        }

        $this->getAttributesObject()->set('id', func_get_arg(0));

        return $this;
    }

    public function disabled(): string|bool|static|null
    {
        if (func_num_args() === 0) {
            return $this->getAttributesObject()->get('disabled');
        }

        $this->getAttributesObject()->set('disabled', func_get_arg(0));

        return $this;
    }
}
