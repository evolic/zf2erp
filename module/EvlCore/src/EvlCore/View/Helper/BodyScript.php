<?php


namespace EvlCore\View\Helper;

use Zend\View\Helper\HeadScript;

/**
 * Helper for setting and retrieving script elements for HTML head section
 *
 * Allows the following method calls:
 * @method BodyScript appendFile($src, $type = 'text/javascript', $attrs = array())
 * @method BodyScript offsetSetFile($index, $src, $type = 'text/javascript', $attrs = array())
 * @method BodyScript prependFile($src, $type = 'text/javascript', $attrs = array())
 * @method BodyScript setFile($src, $type = 'text/javascript', $attrs = array())
 * @method BodyScript appendScript($script, $type = 'text/javascript', $attrs = array())
 * @method BodyScript offsetSetScript($index, $src, $type = 'text/javascript', $attrs = array())
 * @method BodyScript prependScript($script, $type = 'text/javascript', $attrs = array())
 * @method BodyScript setScript($script, $type = 'text/javascript', $attrs = array())
 */
class BodyScript extends HeadScript
{
}
