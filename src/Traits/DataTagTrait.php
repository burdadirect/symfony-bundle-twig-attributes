<?php

namespace HBM\TwigAttributesBundle\Traits;

use HBM\TwigAttributesBundle\Utils\HtmlAttributes;
use HBM\TwigAttributesBundle\Utils\HtmlTag;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @property string $label
 * @property bool $translated
 * @property array $fieldsToTranslate
 *
 * @property TranslatorInterface $translator
 * @property array $translatorParameters
 * @property string|null $translatorDomain
 * @property string|null $translatorLocale
 *
 * @method static array _data();
 */
trait DataTagTrait
{
    public static function tag(string|int|bool $key = null, string $field = null, array|HtmlAttributes $attributes = null): ?HtmlTag
    {
        $fieldToUse = $field ?: static::$label;
        if (($key !== null) && (isset(static::_data()[$key][$fieldToUse]))) {
            $value = static::_data()[$key][$fieldToUse];

            $tag = new HtmlTag('data', $attributes);
            $tag->set('data-class', static::class);
            $tag->set('data-key', $key);
            $tag->set('data-field', $fieldToUse);
            if (count(static::$fieldsToTranslate) > 0) {
                $tag->set('data-translated', static::$translated);
                if (!static::$translated) {
                    if (count(static::$translatorParameters) > 0) {
                        $tag->set('data-translator-parameters', json_encode(static::$translatorParameters));
                    }
                    if (static::$translatorDomain) {
                        $tag->set('data-translator-domain', static::$translatorDomain);
                    }
                    if (static::$translatorLocale) {
                        $tag->set('data-translator-locale', static::$translatorLocale);
                    }
                }
            }
            $tag->set('value', $value);
            $tag->content($value);

            return $tag;
        }

        return null;
    }
}
