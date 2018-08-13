# Complex Category

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/silvercommerce/complex-category/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/silvercommerce/complex-category/?branch=1.0)

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

## Customising sort and page length options

You can add custom sort and page length options via SilverStripe config. The config options
are as follows.

### ComplexCategoryController.sort_options

An array of sort options, where the key is the sort (which is loaded into the `DataList::sort()`
call) and the value is what is loaded in the dropdown.

If you wanted to add a "Date Added" field to the sort, you could add the following to your `config.yml`

```yml
ComplexCategoryController:
  sort_options:
    "Created ASC": "Added (most recent first)"
    "Created DESC": "Added (oldest first)"
```

### ComplexCategoryController.show_options

An array of page length options, where the key is the length (used by `PaginatedList::setPageLength()`)and the value is what is loaded in the dropdown.

If you wanted to add 120 and 150 to the field, you could add the following to your `config.yml`

```yml
ComplexCategoryController:
  show_options:
    "120": "120"
    "150": "150"
```

**NOTE:** To attempt to help protect against attacks such as SQL injection, none of the above pass
queries are passed via the URL, instead a index of the selected option is passed and then the
controller selects the appropriate setting.
