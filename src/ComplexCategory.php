<?php

namespace SilverCommerce\ComplexCategory;

use Category;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;

class ComplexCategory extends Category
{
    private static $table_name = 'ComplexCategory';

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

        if ($query[$type]) {
            $i = 0;
            foreach ($options as $key => $value) {
                if ($query[$type] == $i
                && $key != ComplexCategoryController::DEFAULT_SORT) {
                    $return = $key;
                    break;
                }
                $i++;
            }
        }

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
        $limit_options = Config::inst()->get(
            ComplexCategoryController::class,
            "limit_options"
        );

        $sort = $this->getCurrentOption($sort_options, "sort");
        $limit = $this->getCurrentOption($limit_options, "limit");

        if (!empty($sort)) {
            $products = $products->sort($sort);
        }

        if (!empty($limit)) {
            $products = $products->limit($limit);
        }

        return $products;
    }
}