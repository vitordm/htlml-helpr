<?php

namespace Html;


class BootstrapHelper extends HtmlHelper
{
    const FONT_AWESOME = "font-awesome";
    const FONT_BOOTSTRAP = "font-bootstrap";


    /**
     * @param $tags
     *
     * @return string
     */
    public static function row($tags)
    {
        $tags = implode(PHP_EOL, func_get_args());

        $tag_col = self::tag('div', $tags, "col-lg-12", '', array());

        $row = self::tag('div', $tag_col, "row", '', array());

        return $row;

    }

    /**
     * @param $text
     *
     * @return string
     */
    public static function page_header($text)
    {
        return self::tag('h1', $text, 'page-header');
    }

    /**
     * @param string $panel_type
     * @param string $tags
     *
     * @return string
     */
    public static function panel($panel_type = 'default', $tags = null)
    {
        $tag = self::tag('div', $tags, 'panel panel-'.$panel_type, '', array());
        return $tag;

    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_default($tags)
    {
        return self::panel('default', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_primary($tags)
    {
        return self::panel('primary', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_success($tags)
    {
        return self::panel('success', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_info($tags)
    {
        return self::panel('info', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_warning($tags)
    {
        return self::panel('warning', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_danger($tags)
    {
        return self::panel('danger', $tags);
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_heading($tags)
    {
        $tag = self::tag('div', $tags, 'panel-heading', '', array());
        return $tag;
    }

    /**
     * @param $tags
     *
     * @return string
     */
    public static function panel_body($tags)
    {
        $tag = self::tag('div', $tags, 'panel-body', '', array());
        return $tag;
    }


    /**
     * @param        $icon
     * @param string $type
     *
     * @return string
     */
    public static function icon($icon, $type = self::FONT_AWESOME)
    {
        $class = null;

        if ($type === self::FONT_AWESOME) {
            $class = 'fa fa-' . $icon;

        }
        else if ($type === self::FONT_BOOTSTRAP) {
            $class = 'glyphicon-' . $icon;
        }
        else {
            $class = $icon;
        }

        return self::tag('i', '', $class, '');
    }

}