<?php

namespace SilverCommerce\ComplexCategory;

use Category;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;

class ComplexCategory extends Category
{
    private static $table_name = 'ComplexCategory';

    /**
     * Human-readable singular name.
     * @var string
     * @config
     */
    private static $singular_name = 'Complex Category';

    /**
     * Human-readable plural name
     * @var string
     * @config
     */
    private static $plural_name = 'Complex Categories';


    private static $description = "A category that allows sorting and limiting of products";

    /**
     * Get the current search query from the current request
     * 
     * @return array
     */
    public function getQuery()
    {
        $query = Controller::curr()->getRequest()->getVar("s");

        if (!$query || ($query && !is_array($query))) {
            $query = [];
        }

        $this->extend('updateQuery', $query);

        return $query;
    }

    /**
     * Return the current option from the URL
     * 
     * @param array  $options An array of options to check
     * @param string $type    Either "sort" or "limit"
     * 
     * @return string 
     */
    public function getCurrentOption($options, $type = "sort")
    {
        $query = $this->getQuery();
        $return = "";
        $default_title = ComplexCategoryController::getDefaultSortTitle();

        if (isset($query[$type])) {
            $i = 0;
            foreach ($options as $key => $value) {
                if ($query[$type] == $i
                && $key != $default_title) {
                    $return = $key;
                    break;
                }
                $i++;
            }
        }

        $this->extend('updateCurrentOption', $return);

        return $return;
    }

    /**
     * Get a list of all products from this category and it's children
     * categories.
     *
     * @return ArrayList
     */
    public function AllProducts($sort = [])
    {
        $products = parent::AllProducts($sort);

        $sort_options = Config::inst()->get(
            ComplexCategoryController::class,
            "sort_options"
        );

        $sort = $this->getCurrentOption($sort_options, "sort");

        if (!empty($sort)) {
            $products = $products->sort($sort);
        }

        $this->extend('updateAllProducts', $products);

        return $products;
    }
}