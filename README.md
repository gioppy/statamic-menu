# Simple menu manager for Statamic 2

Create and manage menus for single or multiple languages sites.

![Manage menu](https://web.giovannibuffa.it/github/statamic_menu_index.png)

![Manage menu](https://web.giovannibuffa.it/github/statamic_menu_items.png)

## Installation

1. Download this repository.
2. Move zip to `site/addons` and unzip it.
3. Rename unzipped forder into `Menu`.
4. Done!

## Tag example

The addon has a tag for printing menu structure inside your template.

This is the base tag structure:

`{{ menu src="menu-name" }}`

### All available paramenters

Property|Description|Default
--------|-----------|-------
`src=""`|The slugify name of the menu. This is the only required property.|
`type=""`|The list type of menu.|`ul`
`id=""`|The (CSS) id of the list|
`class=""`|The (CSS) classes of the list|
`element_class=""`|The (CSS) classes of list element|
`link_class=""`|The (CSS) classes of link element|
`active_class=""`|The (CSS) classes applied on link element if the page is active|`active`

For example:

`{{ menu src="main-menu" type="ol" id="main-menu" class="main-menu" element_class="main-menu__element" link_class="main-menu__link" active_class="main-menu__link--active"}}`
