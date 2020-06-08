<?php

namespace SilverCommerce\ComplexCategory;

use CategoryController;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DropdownField;

class ComplexCategoryController extends CategoryController
{
    const FALLBACK_LIMIT = 24;

    /**
     * Specify sort options and their titles
     * (these can be expanded upon using global config)
     * 
     * @var array
     */
    private static $sort_options = [
        'Default' => 'Default',
        'Title ASC' => 'Name (A-Z)',
        'Title DESC' => 'Name (Z-A)',
        'BasePrice ASC' => 'Price (Low - High)',
        'BasePrice DESC' => 'Price (High - Low)'
    ];

    /**
     * List of default limit options (can be amended via config)
     * 
     * @var array
     */
    private static $show_options = [
        '24' => '24',
        '48' => '48',
        '72' => '72',
        '96' => '96'
    ];

    /**
     * Get an i18n friendly version of the sort name
     * 
     * @return string
     */
    public function getTranslatedSort($option)
    {
        return _t(self::class . "." . $option, $option);
    }

    /**
     * Try to determine the default sort title based on the set sort options
     *
     * @return string
     */
    public static function getDefaultSortTitle()
    {
        $options = self::config()->get('sort_options');

        if (count($options) > 0) {
            // slice of the first option (as first item will not be an int)
            return reset($options);
        }

        return "";
    }

    /**
     * Try to determine the default limit amount based on the set limit options.
     * If none is found, fallback to a hard coded default.
     *
     * @return int
     */
    public static function getDefaultLimit()
    {
        $options = self::config()->get('show_options');

        if (count($options) > 0) {
            // slice of the first option (as 0 key might not exist)
            return reset($options);
        }

        return self::FALLBACK_LIMIT;
    }

    /**
     * Generate a sort/limit form
     * 
     * @return Form 
     */
    public function SortLimitForm()
    {
        $sort_options = $this->config()->sort_options;
        $show_options = $this->config()->show_options;

        // Get the current query
        $query = $this->getQuery();

        // Create a new array based on available sort/limit options
        $sort = [];
        $limit = [];

        $i = 0;
        foreach ($sort_options as $key => $value) {
            $sort[$i] = $this->getTranslatedSort($value);
            $i++;
        }

        $i = 0;
        foreach ($show_options as $item) {
            $limit[$i] = $item;
            $i++;
        }

        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                $sort_field = DropdownField::create(
                    "s[sort]",
                    _t(self::class . '.Sort', 'Sort'),
                    $sort
                ),
                $limit_field = DropdownField::create(
                    "s[show]",
                    _t(self::class . '.Show', 'Show'),
                    $limit
                ),
                FormAction::create(
                    "go",
                    _t(self::class . '.Go', 'Go')
                )
            ),
            FieldList::create()
        );

        // Add extra bootstrap class (if required)
        $form
            ->addExtraClass("form-inline")
            ->setTemplate('SilverCommerce\\ComplexCategory\\' . __FUNCTION__)
            ->setFormMethod("GET")
            ->setFormAction($this->Link())
            ->disableSecurityToken();

        if (!empty($query["sort"])) {
            $sort_field->setValue($query["sort"]);
        }

        if (!empty($query["show"])) {
            $limit_field->setValue($query["show"]);
        }

        $this->extend("updateSortLimitForm", $form);

        return $form;
    }

    /**
     * Get the pagination limit selected (either via the URL or by default)
     * 
     * @return int
     */
    protected function getPaginationLimit()
    {
        $default_limit = self::getDefaultLimit();
        $show_options = Config::inst()->get(self::class, "show_options");
        $new_limit = $this->getCurrentOption($show_options, "show");

        if (!empty($new_limit)) {
            $limit = $new_limit;
        } else {
            $limit = $default_limit;
        }

        return (int)$limit;
    }

    /**
     * Get a paginated list of all products at this level and below
     * 
     * This is expanded to support the length dropdown
     *
     * @return PaginatedList
     */
    public function PaginatedProducts($limit = 10)
    {
        $limit = $this->getPaginationLimit();

        return parent::PaginatedAllProducts($limit);
    }

    /**
     * Get a paginated list of all products at this level and below
     * 
     * This is expanded to support the length dropdown
     *
     * @return PaginatedList
     */
    public function PaginatedAllProducts($limit = 10)
    {
        $limit = $this->getPaginationLimit();

        return parent::PaginatedAllProducts($limit);
    }
}