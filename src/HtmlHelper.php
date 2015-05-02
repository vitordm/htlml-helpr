<?php

namespace Html;

/**
 * Classe de auxilio no para HTML
 * @version 0.1
 * @author Vitor Oliveira <oliveira.vitor3@gmail.com>
 */
class HtmlHelper
{
    protected $BASE_PATH;
    
    /**
     * Constroi a classe
     * @var string $base_path pre configura o base path
     */ 
    public function __construct($base_path = NULL)
    {
        $this->setBasePath($base_path);
    }
    
    /**
     * Retorna o base path
     * @return string
     */
    public function getBasePath()
    {
        return $this->BASE_PATH;
    }
    
    /**
     * Configura o base path
     * @var string $base_path
     */ 
    public function setBasePath($base_path)
    {
        $this->BASE_PATH = $base_path;
    }
    
    public function doctype()
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
    public function tag($type, $text = NULL,  $class = NULL, $id = NULL, $attrs = array())
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
        $tag .= ' ' . $this->parseAttrs($attrs);
        
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
    public function tags()
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
            
            $ret .= $this->tag($type, $text, $class, $id, $a) . PHP_EOL;
            
        }
        
        return $ret;
    }
    
    /**
     * Retorna a tag <br>
     * @return string
     */
    public function br()
    {
        return $this->tag('br');
    }
    
    /**
     * Retorna a tag <span>
     * @param string $text
     * @param array $attrs
     * @return string
     */
    public function span($text, $attrs = array())
    {
        return $this->tag('span',$text, null, null,$attrs);
    }
    
    /**
     * Retorna a tag <hr>
     * @return string
     */
    public function hr()
    {
        return $this->tag('hr');
    }

    /**
     * Realiza o parse dos atributos em uma tag
     * @param array $attrs array('atributo' => 'valor')
     * @throws HtmlException
     * @return string
     */
    protected function parseAttrs($attrs = array())
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
    protected function parseBreak($string)
    {
        return PHP_EOL . $string . PHP_EOL;
    }
    
    /**
     * Gera a tag de script
     * @param array|string $fileName
     * @param bool $base_path
     * @return string
     */
    public function js($fileName, $base_path = false)
    {
        if (!is_array($fileName))
            $fileName = array($fileName);
        $data = null;
        foreach ($fileName as $file)
        {
            $data .= '<script src="';
            $data .= ($base_path) ? $this->BASE_PATH : '';
            $data .= $file . '"></script>' . PHP_EOL;
        }
        return $data;
    }

    /**
     * Gera a tag de link
     * @param array|string $fileName
     * @return string
     */
    public function css($fileName)
    {
        if (!is_array($fileName))
            $fileName = array($fileName);
        $data = null;
        foreach ($fileName as $file)
        {
            $data .= '<link rel="stylesheet" type="text/css" href="' . $file . '"/>' . PHP_EOL;
        }
        
        return $data;
    }
    
    public function a($path, $text = NULL, $base_path = true, array $attrs = array())
    {
        if($base_path)
            $path = $this->BASE_PATH . $path;
        $attrs['href'] = $path;
            
        return $this->tag('a', $text, false, false, $attrs);
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function shortenUrls($data)
    {
        $data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class(self), 'fetchTinyUrl'), $data);
        return $data;
    }

    /**
     * @param $url
     * @return string
     */
    public static function fetchTinyUrl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url[0]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return '<a href="' . $data . '" target = "_blank" >' . $data . '</a>';
    }

    /**
     * @param $path
     * @param bool $base_path
     * @return null|string
     */
    public function url($path, $base_path = true)
    {
        $url = null;
        if($base_path)
            $url .= trim($this->BASE_PATH, '/') . '/';
        $url .= $path;

        return $url;
    }

}
