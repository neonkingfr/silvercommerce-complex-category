# Complex Category

Category that allows sorting of products and selecting pagination length

## Installation

Install this module using composer

    composer require silvercommerce/complex-category

## Usage

This module adds a new category type called "Complex Category" that you can add
via the admin interface. These categories allow you to add a `SortLimitForm`
that will add "sort" and "show" dropdowns and automatically update the product list.

## Add Form to Template

This module requires you to add a `$SortLimitForm` variable to your category templates.

You can add this to all category templates, or specify a specific template for your
complex category. 