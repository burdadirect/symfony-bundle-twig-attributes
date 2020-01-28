# HBM Twig Attributes Bundle

## Team

### Developers
Christian Puchinger - christian.puchinger@playboy.de

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require burdanews/symfony-bundle-twig-attributes 
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

With Symfony 4 the bundle is enabled automatically for all environments (see `config/bundles.php`). 

### Step 3: Configuration

Currently no configuration is available.

```yml
hbm_twig_attributes:
```

## Usage

```twig
{% set attributes = attributes({'class': ['class1', 'class2']}) %}
{% set attributes = attributes.set('placeholder', 'This is a placeholder').set('type', 'text') %}
{% set attributes = attributes.unset('type').set('data-something', '') %}
{% set attributes = attributes.setIfEmpty('data-something', 'some value') %}
{% set attributes = attributes.addClasses(['class3', 'class4']) %}

{% set placeholder = attributes.get('placeholder') %}
```

```twig
<a {{ attributes() }} />

<div {{ attributes().id('this-is-an-id') }}>Lore ipsum...</div>
```

#### Shorthand

Available for:
- title
- target
- href
- class
- onclick
- id

```twig
{% set attributes = attributes.href('http://www.burda.com') %}
{% set href = attributes.href() %}

{% set attributes = attributes.href('https://www.burda.com', '_blank') %}
```

