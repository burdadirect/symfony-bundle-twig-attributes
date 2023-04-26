<?php

namespace HBM\TwigAttributesBundle\Utils;

use Exception;

class HtmlAttributes {

  use HtmlAttributesTrait;

  protected static array $standalone = [
    'selected', 'checked', 'disabled', 'readonly', 'multiple',
    'noresize', 'compact', 'ismap', 'nowrap', 'declare', 'defer', 'noshade'
  ];

  protected array $classes = [];

  protected array $attributes = [];

  /**
   * HtmlAttributes constructor.
   *
   * @param HtmlAttributes|array|string|null $attributes
   * @param mixed $onlyIfNotEmpty
   */
  public function __construct(HtmlAttributes|array|string|null $attributes = NULL, mixed $onlyIfNotEmpty = FALSE) {
    if ($attributes instanceof self) {
      $this->classes = $attributes->getClasses();
      $this->attributes = $attributes->getAttributes();
    } elseif (is_array($attributes)) {
      $this->add($attributes, $onlyIfNotEmpty);
    } elseif (is_string($attributes)) {
      $this->add($this->parseString($attributes), $onlyIfNotEmpty);
    }
  }

  /**
   * Returns a copy of the html attributes.
   *
   * @return self
   */
  public function copy(): HtmlAttributes {
    $copy = new HtmlAttributes();
    $copy->setClasses($this->getClasses());
    $copy->setAttributes($this->getAttributes());

    return $copy;
  }

  /****************************************************************************/

  /**
   * @param string[] $classes
   *
   * @return static
   */
  public function setClasses(array $classes) : static {
    $this->classes = $classes;

    return $this;
  }

  /**
   * @return string[]
   */
  public function getClasses() : array {
    return $this->classes;
  }

  /**
   * @param string[] $attributes
   *
   * @return static
   */
  public function setAttributes(array $attributes) : static {
    $this->attributes = $attributes;

    return $this;
  }

  /**
   * @return array
   */
  public function getAttributes() : array {
    return $this->attributes;
  }


  /**
   * Returns all attribute keys.
   *
   * @return array
   */
  public function keys() : array {
    return array_keys($this->attributes);
  }

  /****************************************************************************/

  /**
   * Sets multiple html attributes.
   *
   * @param HtmlAttributes|array|string|null $attributes
   * @param mixed $onlyIfNotEmpty
   *
   * @return static
   */
  public function add(HtmlAttributes|array|string|null $attributes, mixed $onlyIfNotEmpty = FALSE) : static {
    if (is_array($attributes)) {
      foreach ($attributes as $key => $value) {
        if (!$onlyIfNotEmpty || $value) {
          $this->set($key, $value);
        }
      }
    } elseif (is_string($attributes)) {
      $this->add($this->parseString($attributes), $onlyIfNotEmpty);
    } elseif ($attributes instanceof self) {
      $this->add($attributes->getAttributes());
      $this->addClasses($attributes->getClasses());
    }

    return $this;
  }

  /**
   * Sets an html attribute.
   *
   * @param string $key
   * @param mixed $value
   * @param mixed $condition
   *
   * @return static
   */
  public function set(string $key, mixed $value = null, mixed $condition = TRUE) : static {
    if ($condition) {
      if ($key === 'class') {
        $this->addClasses($value);
      } else {
        $this->attributes[$key] = $value;
      }
    }

    return $this;
  }

  /**
   * Sets an html attribute.
   *
   * @param array|string $keys
   *
   * @return static
   */
  public function unset(array|string $keys) : static {
    if (!is_array($keys)) {
      $keys = [$keys];
    }
    foreach ($keys as $key) {
      unset($this->attributes[$key]);
    }

    return $this;
  }

  /**
   * Sets an html attribute if the value does not exist or is empty.
   *
   * @param $key
   * @param $value
   *
   * @return static
   */
  public function setIfEmpty($key, $value) : static {
    if (!$this->get($key)) {
      return $this->set($key, $value);
    }

    return $this;
  }

  /**
   * Sets a html attribute if the value is not null or empty.
   *
   * @param string $key
   * @param mixed $value
   *
   * @return static
   */
  public function setIfNotEmpty(string $key, mixed $value) : static {
    if ($value) {
      return $this->set($key, $value);
    }

    return $this;
  }

  /**
   * Gets a html attribute.
   *
   * @param string $key
   *
   * @return mixed
   */
  public function get(string $key): mixed {
    if ($key === 'class') {
      return $this->classes;
    }

    return $this->attributes[$key] ?? NULL;
  }

  /****************************************************************************/

  /**
   * @param array|string $classes
   *
   * @return static
   */
  public function addClasses(array|string $classes) : static {
    if (!is_array($classes)) {
      $classes = explode(' ', $classes ?? '');
    }

    $classes = array_map('trim', $classes);
    $classes = array_merge($this->classes, $classes);
    $classes = array_unique($classes);

    $this->classes = array_diff($classes, ['']);

    return $this;
  }

  /**
   * @param array|string $classes
   * @return static
   */
  public function removeClasses(array|string $classes) : static {
    if (!is_array($classes)) {
      $classes = explode(' ', $classes);
    }

    $classes = array_map('trim', $classes);
    $classes = array_unique($classes);

    $this->classes = array_diff($this->classes, $classes, ['']);

    return $this;
  }

  /**
   * @param $class
   *
   * @return bool
   */
  public function hasClass($class) : bool {
    return in_array($class, $this->classes, TRUE);
  }

  /****************************************************************************/

  /**
   * @return array
   */
  public function toArray() : array {
    $all = [];

    if (count($this->classes) > 0) {
      $all['class'] = implode(' ', $this->classes);
    }

    foreach ($this->attributes as $key => $value) {
      if (in_array($key, self::$standalone, TRUE)) {
        if ($value) {
          $all[$key] = $key;
        }
      } else {
        $all[$key] = $value;
      }
    }

    return $all;
  }

  /**
   * @param string $attributesString
   *
   * @return array
   */
  private function parseString(string $attributesString): array {
    if (preg_match_all('/\s?(.*?)="(.*?)"\s?/', $attributesString, $matches)) {
      return array_combine($matches[1], $matches[2]);
    }

    return [];
  }

  /**
   * @return string
   *
   * @throws \JsonException
   */
  public function __toString() {
    try {
      $parts = [];
      foreach ($this->toArray() as $key => $value) {
        if ($value === null) {
          $parts[] = $key;
        } elseif (is_bool($value)) {
          $parts[] = $key.'="'.($value ? 'true': 'false').'"';
        } else {
          $parts[] = $key.'="'.str_replace('"', '&quot;', $value).'"';
        }
      }

      return implode(' ', $parts);
    } catch (Exception $e) {
      return 'data-exception="'.htmlentities(json_encode($this->attributes, JSON_THROW_ON_ERROR), ENT_COMPAT).'"';
    }
  }

}
