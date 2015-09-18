<?php

namespace Html;

/**
 * Class HtmlHelper
 * @version 1.0.0
 * @package Html
 * @author Vitor Oliveira <oliveira.vitor3@gmail.com>
 * @license
 */
class HtmlHelper
{
    /** @var string */
    public static $BASE_SITE;
    /** @var string */
    public static $CSS_LINK;
    /** @var string */
    public static $JS_LINK;


    /**
     * get the instace of this class
     * @return HtmlHelper
     */
    public static function &getInstance()
    {
        static $instance;

        if (!is_object($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * Retorna o base path
     * @return string
     */
    public static function getBaseSite()
    {
        return rtrim(self::$BASE_SITE, "/") . "/";
    }
    
    /**
     * Configura o base path
     * @var string $site
     */
    public static function setBaseSite($site)
    {
        self::$BASE_SITE = $site;
    }

    /**
     * @param string $link
     */
    public static function setCSSLink($link)
    {
        self::$CSS_LINK = $link;
    }

    /**
     * @param $link
     * @return string
     */
    public static function getCSSLink($link)
    {
        return rtrim(self::$CSS_LINK, "/") . "/";
    }

    public static function setJSLink($link)
    {
        self::$JS_LINK = $link;
    }

    public static function getJSLink()
    {
        return rtrim(self::$JS_LINK, "/") . "/";
    }

    /**
     * @return string
     */
    public static function doctype()
    {
        return "<!DOCTYPE html>";
    }
    
    /**
     * Realiza a criação da tag
     * Se colocado a mesagem, cria a dupla exemplo: <tag>MENSAGEM<tag>;
     * Se não passado uma mensagem, cria uma tag simples ex: <hr/>
     * @param string $type tipo da tag
     * @param string $text mensagem caso a tag for dupla
     * @param string $class atributo class da tag
     * @param string $id atributo id da tag
     * @param array $attrs Atributos da tag
     * @return string
     */
    public static function tag($type, $text = NULL,  $class = NULL, $id = NULL, $attrs = array())
    {
        /**
         * Monta os atributos primários
         */
        if($class)
            $attrs['class'] = $class;
        if($id)
            $attrs['id'] = $id;
        
        $tag = '<';
        $tag .= $type;
        $tag .= ' ' . self::parseAttrs($attrs);
        
        /**
         * Verifica se a tag é dupla ou unica
         */
        if(!is_null($text) and $text !== false)
            $tag .= '>' . $text . '</' . $type . '>';
        else
            $tag .= '/>';
            
        return $tag;
    }
    
    /**
     * Retorna a criação de tags, deve ser passada um (ou vários) arrays com as descrições da tags
     * exemplo:  tags(array('class' => 'r-t', 'id'=> 'id-1', 'name' => 'aaa', 'text' => ''), [...] )
     * @param array, [...]
     * @return string
     */
    public static function tags()
    {
        $args = func_get_args();
        $ret = NULL;
        foreach($args as $a)
        {
            if(isset($a['class']))
                $class = $a['class'];
            if(isset($a['id']))
                $id    = $a['id'];
            if(isset($a['type']))
                $type  = $a['type'];
            if(isset($a['text']))
                $text = $a['text'];
            
            unset($a['class'],$a['id'], $a['type'],$a['text']);
            
            $ret .= self::tag($type, $text, $class, $id, $a) . PHP_EOL;
            
        }
        
        return $ret;
    }
    
    /**
     * Retorna a tag <br>
     * @return string
     */
    public static function br()
    {
        return self::tag('br');
    }
    
    /**
     * Retorna a tag <span>
     * @param string $text
     * @param array $attrs
     * @return string
     */
    public static function span($text, $attrs = array())
    {
        return self::tag('span',$text, null, null,$attrs);
    }
    
    /**
     * Retorna a tag <hr>
     * @return string
     */
    public static function hr()
    {
        return self::tag('hr');
    }

    /**
     * Realiza o parse dos atributos em uma tag
     * @param array $attrs array('atributo' => 'valor')
     * @throws HtmlException
     * @return string
     */
    protected static function parseAttrs($attrs = array())
    {
        /**
         * Verifica se está passando um array correto
         */
        if(!is_array($attrs))
            throw new HtmlException('Sem itens para parsear' . __CLASS__ . '::' . __FUNCTION__, 1);
        
        /**
         * Monta o retorno
         */
        $return = NULL;
        foreach($attrs as $atr => $val)
        {
            $return .= ' ';
            $return .= trim($atr) . '="' . $val . '"';
        }
        
        $return = trim($return);
        
        return $return;
        
    }

    /**
     * Parsea a string com quebras de linha
     * @param string $string string a ser parseada
     * @return string
     */
    protected static function parseBreak($string)
    {
        return PHP_EOL . $string . PHP_EOL;
    }
    
    /**
     * Gera a tag de script
     * @param array|string $fileName
     * @param bool $base_path
     * @return string
     */
    public static function js($fileName, $base_path = true)
    {
        if (!is_array($fileName))
            $fileName = array($fileName);
        $data = null;
        foreach ($fileName as $file)
        {
            $data .= '<script src="';
            $data .= ($base_path) ? self::getJSLink() : '';
            $data .= $file . '"></script>' . PHP_EOL;
        }
        return $data;
    }

    /**
     * Gera a tag de link
     * @param array|string $fileName
     * @param bool $base_path
     * @return string
     */
    public static function css($fileName, $base_path = true)
    {
        if (!is_array($fileName))
            $fileName = array($fileName);
        $data = null;
        foreach ($fileName as $file)
        {
            $url = "";
            $url .= ($base_path) ? self::getCSSLink() : '';
            $url .= $file;
            $data .= '<link rel="stylesheet" type="text/css" href="' . $url . '"/>' . PHP_EOL;
        }
        
        return $data;
    }

	/**
	 * @param       $path
	 * @param null  $text
	 * @param bool  $base_path
	 * @param array $attrs
	 *
	 * @return string
	 */
	public static function a($path, $text = NULL, $base_path = true, array $attrs = array())
    {
        if($base_path)
            $path = self::getBaseSite() . ltrim($path, '/');
        $attrs['href'] = $path;
            
        return self::tag('a', $text, false, false, $attrs);
    }

	/**
	 * @param string $url
	 *
	 * @return string
	 * @throws \HtmlException
	 */
    public static function shortenUrl($url)
    {

	    $data = self::fetchTinyUrl($url);
	    if ($data)
	        return self::a($data, $data, false, array('target' => '_blank'));

	    throw new \HtmlException("Wrong get a tinyUrl" . print_r($data));
    }

	/**
	 * @param $url
	 *
	 * @return mixed
	 */
    public static function fetchTinyUrl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

	    return $data;
    }

    /**
     * @param $path
     * @param bool $base_path
     * @return null|string
     */
    public static function url($path, $base_path = true)
    {
        $url = null;
        if($base_path)
            $url .= self::getBaseSite();
        $url .= $path;

        return $url;
    }

    /**
     * @param array $head
     * @param array $body
     * @param array $footer
     * @param array $attrs
     *
     * @return null|string
     * @throws \Html\HtmlException
     */
    public static function table($head, $body, $footer = array(), $attrs = array())
    {

        $table = null;

        if (!is_array($head) or !is_array($body)) {
            throw new HtmlException("Parâmetros não são arrays!");
        }

        //Creating head table
        $tr_heads = null;

        if (!isset($head[0])) {
            $head = array($head);
        }

        foreach ($head as $h){

            $tr_head_tag = 'tr';
            $tr_head_attr = array();

            if (isset($h['tr'])) {
                if (isset($h['tr']['tag']))
                    $tr_head_tag = $h['tr']['tag'];
                if(isset($h['tr']['@attrs'])) {
                    $tr_head_attr = $h['tr']['@attrs'];
                }
                unset($h['tr']);
            }

            $td_h = self::parseFetchTdTable($h, 'th');

            $th_row = self::tag($tr_head_tag, $td_h, null, null, $tr_head_attr);
            $tr_heads .= $th_row;
        }

        $table .= self::tag('thead',$tr_heads, null, null, array()) . PHP_EOL;


        //Creating body table
        if (!isset($body[0])) {
            $body = array($body);
        }

        $tr_bodys = null;
        foreach ($body as $b) {

            $tr_body_tag = 'tr';
            $tr_body_attr = array();

            if (isset($b['tr'])) {
                if (isset($b['tr']['tag']))
                    $tr_body_tag = $b['tr']['tag'];
                if(isset($b['tr']['@attrs'])) {
                    $tr_body_attr = $b['tr']['@attrs'];
                }
                unset($b['tr']);
            }

            $td_b = self::parseFetchTdTable($b);
            $tb_row = self::tag($tr_body_tag, $td_b, null, null, $tr_body_attr);
            $tr_bodys .= $tb_row;
        }

        $table .= self::tag('tbody',$tr_bodys, null, null, array()). PHP_EOL;

        //Creating footer of table
        if (!empty($footer)) {

            $tr_footer = null;
            foreach ($footer as $f) {

                $tr_footer_tag = 'tr';
                $tr_footer_attr = array();

                if (isset($f['tr'])) {
                    if (isset($f['tr']['tag']))
                        $tr_footer_tag = $f['tr']['tag'];
                    if(isset($f['tr']['@attrs'])) {
                        $tr_footer_attr = $f['tr']['@attrs'];
                    }
                    unset($b['tr']);
                }

                $td_f = self::parseFetchTdTable($b);
                $tf_row = self::tag($tr_footer_tag, $td_f, null, null, $tr_footer_attr);
                $tr_footer .= $tf_row;
            }

            $table .= self::tag('tfoot',$tr_footer, null, null, array());
        }

        if (!isset($attrs['class'])) {
            $attrs['class'] = "table table-hover table-bordered table-condensed";
        }

        $table = self::tag("table", $table, null, null, $attrs);

        return $table;

    }

    /**
     * @param array  $fields
     * @param string $tag
     *
     * @return null|string
     */
    private static function parseFetchTdTable($fields, $tag = 'td')
    {


        $td_fetch = null;
        foreach ($fields as $h) {

            $attrs = array();
            $value = null;

            if (is_array($h)) {
                $attrs   = isset($h["@attrs"]) ? $h["@attrs"] : array();
                $value   = isset($h['value']) ? $h['value'] : null;

            }
            else {
                $value = $h;
            }

            $td_fetch .= self::tag($tag, $value, null, null, $attrs);
        }

        return $td_fetch;

    }


}
