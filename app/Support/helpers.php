<?php

if (! function_exists('array_filter_recursive')) {
    /**
     * Recursively filter an array
     */
    function array_filter_recursive(array $array, ?callable $callback = null): array
    {
        $array = is_callable($callback) ? array_filter($array, $callback) : array_filter($array);
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = call_user_func(__FUNCTION__, $value, $callback);
                if (empty($value)) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }
}

if (! function_exists('get_blog_owner_name')) {
    /**
     * Get the blog owner name
     *
     * @param string $locale  Selected locale
     */
    function get_blog_owner_name(string $locale): string
    {
        return $locale === 'hu' ? 'Tar Gergő' : 'Gergő Tar';
    }
}


if (! function_exists('prefix_table_columns')) {
    /**
     * Prefix table columns
     */
    function prefix_table_columns(string $table, array $columns): array
    {
        return array_map(fn ($column) => "{$table}.{$column}", $columns);
    }
}
