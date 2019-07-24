<?php

namespace concepture\core\helpers;

class TreeHelper
{

    public static function buildTree(array &$elements, $parentId = 0, $from = 'id', $to = "title", $parent = 'parent_id')
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element[$parent] == $parentId) {
                $children = static::buildTree($elements, $element[$from]);
                if ($children) {
                    $branch[$element[$to]] = $children;
                }else
                    $branch[$element[$from]] = $element[$to];
                unset($elements[$element[$from]]);
            }
        }

        return $branch;
    }
}

