<?php

namespace Statamic\Addons\Menu;

use Statamic\API\Nav;
use Statamic\Extend\Listener;

class MenuListener extends Listener {
  /**
   * The events to be listened for, and the methods to call.
   *
   * @var array
   */
  public $events = [
    'cp.nav.created' => 'addNavItems',
    'cp.add_to_head' => 'injectMenuStyles',
  ];

  public function addNavItems($nav) {
    $menu = Nav::item('menu')
      ->title('Menu')
      ->route('menu.index')
      ->icon('list');

    $nav->addTo('tools', $menu);
  }

  public function injectMenuStyles() {
    $html = $this->css->tag('styles');
    return $html;
  }
}
