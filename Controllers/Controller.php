<?php


namespace Statamic\Addons\Menu\Controllers;

use Statamic\CP\Publish\ProcessesFields;
use Statamic\CP\Publish\PreloadsSuggestions;

abstract class Controller extends \Statamic\Extend\Controller {
  use ProcessesFields;
  use PreloadsSuggestions;
}