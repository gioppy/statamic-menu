<?php


namespace Statamic\Addons\Menu\Controllers;

use Statamic\API\File;
use Statamic\API\Folder;
use Statamic\API\YAML;
use Illuminate\Http\Request;

class MenuController extends Controller {
  public function index() {
    $files = Folder::disk('storage')->getFilesByType('addons/Menu', 'yaml');
    $menus = collect($files)->map(function ($item) {
      $file = File::disk('storage')->get($item);
      return YAML::parse($file);
    });

    return $this->view('index', [
      'title' => 'Manage Menu\'s',
      'locales' => $this->getAvailableLocales(),
      'menus' => $menus,
    ]);
  }

  public function create() {
    return $this->view('create', [
      'title' => 'Create new Menu',
      'locales' => $this->getAvailableLocales(),
    ]);
  }

  public function store(Request $request) {
    $name = $request->input('name');
    $locale = $request->input('locale');

    $slugName = str_slug($name);

    $this->storage->putYAML($slugName, [
      'slug' => $slugName,
      'name' => $name,
      'locale' => $locale,
      'items' => [],
    ]);

    return [
      'success' => true,
      'message' => trans('cp.saved_success'),
      'redirect' => route('menu.index'),
    ];
  }

  public function destroy(Request $request) {
    $slug = $request->input('slug');

    $this->storage->delete($slug.'.yaml');

    return [
      'success' => true,
      'message' => trans('cp.thing_deleted', ['thing' => 'Menu']),
      'redirect' => route('menu.index'),
    ];
  }

  private function getAvailableLocales() {
    $settingsRaw = File::get('site/settings/system.yaml');
    $settings = YAML::parse($settingsRaw);

    $locales = [];

    foreach ($settings['locales'] as $key => $locale) {
      $locales[] = [
        'value' => $key,
        'text' => $locale['name'],
      ];
    }

    return $locales;
  }
}