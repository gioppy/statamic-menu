<?php

namespace Statamic\Addons\Menu;

use Statamic\API\Content;
use Statamic\API\File;
use Statamic\API\Parse;
use Statamic\Extend\Tags;

class MenuTags extends Tags {

  private $templateMenu;
  private $templateItem;

  public function __construct() {
    parent::__construct();

    $this->templateMenu = $this->getTemplateFile('menu');
    $this->templateItem = $this->getTemplateFile('item');
  }

  /**
   * The {{ menu }} tag
   *
   * @return string|array
   */
  public function index() {
    $menu = $this->storage->getYAML($this->getParam('src'));

    if (count($menu['items']) > 0) {
      return $this->buildTree($menu['items'], $menu['locale']);
    }
  }

  /**
   * Build menu tree
   *
   * @param array $items
   * @param string $locale
   * @return string
   */
  protected function buildTree($items, $locale) {
    $tree = [];

    // set type of list: ol, ul
    $type = $this->getParam('type') ? $this->getParam('type') : 'ul';

    // set HTML attributes of tree
    $attributes = $this->treeAttributes();

    foreach ($items as $item) {
      $tree[] = $this->buildItem($item, $locale);
    }

    return Parse::template($this->templateMenu, compact('tree', 'type', 'attributes'));
  }

  /**
   * @param array $item
   * @param string $locale
   * @return string
   */
  protected function buildItem($item, $locale) {
    $element = [];

    $currentPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if (isset($item['id'])) {
      //load page
      $page = Content::find($item['id']);
      $page->locale($locale);

      $element['link'] = $page->absoluteUrl();
    } else {
      // add url
      $element['link'] = $item['url'];
    }

    // set active class
    if ($element['link'] == $currentPath) {
      $item['attributes']['class'] .= $this->getParam('active_class') ? ' ' . $this->getParam('active_class') : ' is-active';
    }

    // title link
    $element['title'] = $item['title'];

    // tag attributes
    $element['attributes'] = $this->linkAttributes($item['attributes']);

    $element['items'] = [];

    // sub items
    if (count($item['items']) > 0) {
      $element['items'] = $this->buildTree($item['items'], $locale);
    }

    // set list element attributes
    $attributes = $this->elementAttributes();

    return Parse::template($this->templateItem, ['item' => $element, 'attributes' => $attributes]);
  }

  /**
   * Return a template file from this addon.
   *
   * @param string $name The name of the html view file
   *
   * @return string
   */
  private function getTemplateFile($name) {
    return File::get($this->getDirectory() . "/resources/views/tags/{$name}.html");
  }

  /**
   * Build attributes on tree
   *
   * @return string
   */
  private function treeAttributes() {
    $attributes = '';

    if ($this->getParam('id')) {
      $attributes .= ' id="' . $this->getParam('id') . '"';
    }

    if ($this->getParam('class')) {
      $attributes .= ' class="' . $this->getParam('class') . '"';
    }

    return $attributes;
  }

  /**
   * Build attributes on list element
   *
   * @return string
   */
  private function elementAttributes() {
    $attributes = '';

    if ($this->getParam('element_class')) {
      $attributes .= ' class="' . $this->getParam('element_class') . '"';
    }

    return $attributes;
  }

  /**
   * Build attributes on link item
   *
   * @param array $values
   * @return string
   */
  private function linkAttributes(array $values) {
    $attributes = '';

    if ($values['id']) {
      $attributes .= ' id="' . $values['id'] . '"';
    }

    $linkClass = $this->getParam('link_class') ? $this->getParam('link_class') . ' ' : '';

    if ($values['class']) {
      $attributes .= ' class="' . $linkClass . $values['class'] . '"';
    } else {
      $attributes .= ' class="' . $linkClass . '"';
    }

    if ($values['title']) {
      $attributes .= ' title="' . $values['title'] . '"';
    }

    if ($values['target'] != '_self') {
      $attributes .= ' target="' . $values['target'] . '"';
    }

    if ($values['rel']) {
      $attributes .= ' rel="' . $values['rel'] . '"';
    }

    return $attributes;
  }
}
