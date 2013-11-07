<?php
/**
 * User: Rutger
 * Date: 7-11-13
 * Time: 10:18
 */

/**
 * Default routes in the URL example /foo/bar/blah/test/ are as follows:
 *
 * controller: Foo
 * action: actionBar
 * parameters: blah, test
 *
 * Other routes can be defined in this file.
 */

$routing = array(

    /**
     * Other pages
     */
    '#.*#' => array(
        'controller' => 'Content'
    ),
);