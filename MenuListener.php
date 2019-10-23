<?php

namespace Statamic\Addons\Menu;

use Statamic\API\File;
use Statamic\API\Folder;
use Statamic\API\Nav;
use Statamic\API\YAML;
use Statamic\Events\Data\PageDeleted;
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
    PageDeleted::class => 'removeItem',
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

  public function removeItem(PageDeleted $event) {
    $id = $event->id;

    $files = Folder::disk('storage')->getFilesByType('addons/Menu', 'yaml');

    collect($files)
      ->map(function ($item) {
        return YAML::parse(File::disk('storage')->get($item));
      })
      ->each(function ($item) use ($id) {
        $values = $this->searchIds($item['items'], $id);

        if (count($values) > 0) {
          foreach ($values as $index => $value) {
            $parent = preg_replace('/.(.[*id])$/x', '', $index);
            array_forget($item['items'], $parent);

            $path = preg_replace('/(.[*\d+])$/x', '', $parent);
            $items = array_get($item['items'], $path);

            if (is_null($items)) {
              $item['items'] = array_values($item['items']);
            } else {
              $parent = array_values(array_get($item['items'], $path));
              array_set($item['items'], $path, $parent);
            }
          }

          $this->storage->putYAML($item['slug'].'.yaml', $item);
        }
      });
  }

  public function searchIds($tree, $id) {
    return collect(array_dot($tree))
      ->filter(function ($item) use ($id) {
        return $item == $id;
      })
      ->toArray();
  }
}
