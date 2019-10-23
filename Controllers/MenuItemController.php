<?php


namespace Statamic\Addons\Menu\Controllers;


use Illuminate\Http\Request;
use Statamic\API\Content;

class MenuItemController extends Controller {

  /**
   * Get all items
   *
   * @param string $menu
   * @return \Illuminate\View\View
   */
  public function index($menu) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    return $this->view('item.index', [
      'title' => 'Menu items ('.$entity['name'].' - '.$entity['locale'].')',
      'menu' => $entity,
    ]);
  }

  /**
   * Save new item on tree
   *
   * @param Request $request
   * @param string $menu
   * @return array
   */
  public function store(Request $request, $menu) {
    $entity = $this->storage->getYAML($menu.'.yaml');
    $locale = $entity['locale'];

    $item = [
      'items' => [],
    ];

    if ($request->has('page')) {
      $id = $request->input('page');
      // link to exsisting content
      $page = Content::find($id);

      // set locale
      $page->locale($locale);

      $item['id'] = $id;
      $item['title'] = $page->get('title');
      $item['type'] = $page->fieldset()->title();

      // set attributes
      $item['attributes'] = $this->menu_item_attributes();
    } else {
      $data = $request->input('data');

      $item['type'] = 'Custom';
      $item = array_merge($data, $item);

      // set attributes
      $item['attributes'] = $this->menu_item_attributes($data['attributes']);
    }

    if ($request->has('parent')) {
      $entry = array_get($entity['items'], $request->input('parent'));
      $entry['items'][] = $item;
      array_set($entity['items'], $request->input('parent'), $entry);
    } else {
      $entity['items'][] = $item;
    }

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
    ];
  }

  /**
   * Update data of one item
   *
   * @param Request $request
   * @param string $menu
   * @param string $index
   * @return array
   */
  public function update(Request $request, $menu, $index) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    array_set($entity['items'], $index, $request->input('item'));

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
    ];
  }

  /**
   * Delete one item from the tree
   *
   * @param string $menu
   * @param string $index
   * @return array
   */
  public function destroy($menu, $index) {
    $entity = $this->storage->getYAML($menu.'.yaml');

    array_forget($entity['items'], $index);

    $path = preg_replace('/(.[*\d+])$/x', '', $index);
    $items = array_get($entity['items'], $path);

    if (is_null($items)) {
      $entity['items'] = array_values($entity['items']);
    } else {
      $parent = array_values(array_get($entity['items'], $path));
      array_set($entity['items'], $path, $parent);
    }

    $this->storage->putYAML($menu, $entity);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
    ];
  }

  /**
   * Search saved page in a locale
   *
   * @param Request $request
   * @param string $locale
   * @return array
   */
  public function search(Request $request, $locale) {
    $search = $request->query('s');
    $results = Content::all()
      ->filter(function (\Statamic\Data\Content\Content $entry) use ($search, $locale) {
        $entry->locale($locale);
        $title = strtolower($entry->get('title'));
        $find = strpos($title, strtolower($search));
        return is_bool($find) ? false : true;
      })
      ->map(function (\Statamic\Data\Content\Content $entry) {
        return [
          'id' => $entry->get('id'),
          'title' => $entry->get('title'),
          'type' => $entry->fieldset()->title(),
        ];
      })
      ->values()
      ->all();

    return [
      'suggestions' => $results
    ];
  }

  /**
   * Build item attributes
   *
   * @param array $values
   * @return array
   */
  private function menu_item_attributes(array $values = []) {
    return array_merge([
      'title' => '',
      'id' => '',
      'class' => '',
      'rel' => '',
      'target' => '_self'
    ], $values);
  }
}