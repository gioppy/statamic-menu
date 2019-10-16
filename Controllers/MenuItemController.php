<?php


namespace Statamic\Addons\Menu\Controllers;


use Illuminate\Http\Request;
use Statamic\API\Content;
use Statamic\API\File;
use Statamic\API\Page;
use Statamic\Data\Entries\Entry;

class MenuItemController extends Controller {

  public function index($menu) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    return $this->view('item.index', [
      'title' => 'Menu items ('.$entity['name'].' - '.$entity['locale'].')',
      'menu' => $entity,
    ]);
  }

  public function store(Request $request, $menu) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    $item = [
      'attributes' => [
        'title' => '',
        'id' => '',
        'class' => '',
        'rel' => '',
        'target' => '_self'
      ],
      'items' => [],
    ];

    if ($request->has('page')) {
      $id = $request->input('page');
      // link to exsisting content
      $page = Page::find($id);

      $item['id'] = $id;
      $item['title'] = $page->get('title');
      $item['type'] = 'Page';
    }

    $entity['items'][] = $item;

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success')
    ];
  }

  public function update(Request $request, $menu, $index) {
    $entity = $this->storage->getYAML($menu.'.yaml');
    $entity['items'][$index] = $request->input('data');

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
    ];
  }

  public function destroy($menu, $index) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    unset($entity['items'][$index]);
    $entity['items'] = array_values($entity['items']);

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
    ];
  }

  public function search(Request $request, $locale) {
    $search = $request->query('s');
    $results = Page::all()
      ->filter(function (\Statamic\Data\Pages\Page $entry) use ($search) {
        $title = $entry->get('title');
        $find = strpos($title, $search);
        return is_bool($find) ? false : true;
      })
      ->map(function (\Statamic\Data\Pages\Page $entry) {
        //return [$entry->get('id') => $entry->get('title')];
        return [
          'id' => $entry->get('id'),
          'title' => $entry->get('title'),
        ];
      })
      ->values()
      ->all();

    return [
      'suggestions' => $results
    ];
  }
}