<?php

use Illuminate\Support\Str;

/**
 * Get class name of passed string with options.
 *
 * @param  string  $class
 * @param  bool  $withNamespace (optional)
 * @param  string  $namespace (optional namespace default to models namespace)
 * @param  string  $trailing (optional trailing class namespace string)
 * @return string class name with/without namespace
 */
if (! function_exists('class_name_of')) {
    function class_name_of
    (
        string $class,
        bool $withNamespace = false,
        string $namespace = 'SolutionPlus\\DynamicPages\\Models\\',
        $trailing = ''
    ) {
        $class = Str::camel(str_replace('-', '_', $class));
        $class = Str::singular(ucfirst($class));
        $trailing = ucfirst($trailing);
        return (bool) $withNamespace ? $namespace . $class . $trailing : $class . $trailing;
    }
}

if (! function_exists('pagination_length')) {
    function pagination_length($modelName, $length = 20) {
        $paginationLength = request()->pagination;
        $model = class_name_of($modelName, ! \str_contains($modelName, "\\"));
        if ($paginationLength == 'all') {
            $modelRaws = $model::count();
            return $modelRaws <= 500 ? $modelRaws : 500;
        }
        if (is_numeric($paginationLength)) {
            return $paginationLength;
        }
        return (int) $length;
    }
}

if (!function_exists('random_by')) {
    function random_by($num = 6)
    {
        $start = (int) \str_repeat(1, $num);
        $end = (int) \str_repeat(9, $num);
        return \rand($start, $end);
    }
}

if (!function_exists('format_json_strings_to_array')) {
    function format_json_strings_to_array(array $fields)
    {
        $fieldsToBeMerged = [];
        for ($i = 0; $i < \count($fields); $i++) {
            $field = $fields[$i];
            if (request()->exists($field)) {
                $fieldsToBeMerged[$field] = \gettype(request()->$field) == 'string' ? (array) \json_decode(request()->$field) : request()->$field;
            }
        }
        return $fieldsToBeMerged;
    }
}

if (!function_exists('format_json_strings_to_boolean')) {
    function format_json_strings_to_boolean(array $fields, bool $includeBooleanPrefix = true)
    {
        $prefix = $includeBooleanPrefix ? 'is_' : '';
        $fieldsToBeMerged = [];
        for ($i = 0; $i < \count($fields); $i++) {
            $field = $fields[$i];
            if (request()->exists($field)) {
                $fieldsToBeMerged["{$prefix}{$field}"] = \gettype(request()->$field) == 'string' ? (bool) \json_decode(request()->$field) : request()->$field;
            }
        }
        return $fieldsToBeMerged;
    }
}
