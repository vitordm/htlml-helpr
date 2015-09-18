<?php

namespace Html;

/**
 * Class FormHelper
 * @version 1.0.0
 * @package Html
 * @author Vitor Oliveira <oliveira.vitor3@gmail.com>
 */
class FormHelper extends HtmlHelper
{
	/**
	 * @param null   $name
	 * @param null   $class
	 * @param null   $id
	 * @param string $method
	 * @param string $action
	 * @param array  $attrs
	 *
	 * @return null|string
	 * @throws HtmlException
	 */
    public static function open($name = NULL,
                         $class = NULL,
                         $id = NULL,
                         $method = 'POST',
                         $action = '',
                         $attrs = array()
                         )
    {
        $tag = NULL;
        
        if($class)
            $attrs['class'] = $class;
        if($id)
            $attrs['id'] = $id;
        $attrs['method'] = $method;
        $attrs['action'] = $action;
        
        $tag .= '<form';
        $tag .= ' ' . self::parseAttrs($attrs);
        $tag .= '>';
        return $tag;
    }
    
    /**
     * @return string "</form>"
     */
    public static function close()
    {
        return "</form>";
    }
	/**
	 * Cria a tag Select com suas diretivas
	 * @param       $name
	 * @param       $class
	 * @param       $id
	 * @param array $options
	 * @param null  $opt_value
	 * @param null  $opt_group
	 * @param null  $label
	 * @param array $attrs
	 *
	 * @return null|string
	 */
    public static function select($name,
                           $class,
                           $id,
                           array $options,
                           $opt_value = NULL,
                           $opt_group = NULL,
                           $label = NULL,
                           $attrs = array())
    {
        $attrs['name'] = $name;
        
        $opts = array();
        foreach($options as $value => $msg)
        {
            $opt_attr = array(
              'value' => $value 
            );
            
            if(($opt_value) && $value == $opt_value)
                $opt_attr['selected'] = 'selected';
            
            $opts[] = self::tag('option', $msg, NULL, NULL, $opt_attr);
            
        }
        
        $opts = self::parseBreak(implode(PHP_EOL, $opts));
        $ret = NULL;
        if($label)
            $ret = PHP_EOL . self::formatLabel($label, $id);
            
        $ret .= self::parseBreak(self::tag('select', $opts, $class, $id, $attrs));
        return $ret;
    }

	/**
	 * @param       $name
	 * @param null  $class
	 * @param null  $id
	 * @param null  $value
	 * @param null  $label
	 * @param array $attrs
	 *
	 * @return null|string
	 */
	public static function textarea($name,
                             $class = NULL,
                             $id = NULL,
                             $value = NULL,
                             $label = NULL,
                             $attrs = array())
    {
        $ret = NULL;
        if($label)
            $ret .= PHP_EOL . self::formatLabel($label, $id);
        if(!$value)
            $value = '';
        $attrs['name'] = $name;
        $ret .= self::tag('textarea',$value, $class, $id, $attrs);
        
        return $ret;
        
    }

	/**
	 * Gera input
	 * @param string $type
	 * @param string $name
	 * @param string $class
	 * @param null   $id
	 * @param null   $value
	 * @param null   $label
	 * @param array  $attrs
	 *
	 * @return null|string
	 */
    public static function input(
                        $type = 'text',
                        $name = '',
                        $class = '',
                        $id = NULL,
                        $value = NULL,
                        $label = NULL,
                        $attrs = array()
                        )
    {
        
        $attrs['type'] = $type;
        
        $ret = NULL;
        if($label)
            $ret .= PHP_EOL . self::formatLabel($label, $id);
        
        if($value)
            $attrs['value'] = $value;
        
        $attrs['name'] = $name;
        
        $ret .= self::tag('input', NULL, $class, $id, $attrs);
        return $ret;
    }

	/***
	 * @param null  $name
	 * @param null  $class
	 * @param null  $id
	 * @param null  $text
	 * @param array $attrs
	 *
	 * @return null|string
	 */
    public static function button(
        $name = NULL,
        $class = NULL,
        $id = NULL,
        $text = NULL,
        $attrs = array()
    )
    {
        $ret = NULL;
        
           if($name)
            $attrs['name'] = $name;
        
        $ret .= self::tag('button', $text, $class, $id, $attrs);
        return $ret;
    }
    
    /**
     * Cria Label para algum formulário
     */
    private function formatLabel($label, $id = NULL, $attrs = array())
    {
        if($id)
            $attrs['for'] = $id;
        $ret = self::tag('label', $label, NULL, NULL, $attrs);
        return $ret;
    }
}

