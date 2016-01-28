<?php
namespace BOTK\Core;

/*
 * A simple template engine inspired by http://github.com/sebastianbergmann/php-text-template
 */
class SimpleTemplateEngine
{
    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var string
     */
    protected $openDelimiter = '{';

    /**
     * @var string
     */
    protected $closeDelimiter = '}';

    /**
     * @var array
     */
    protected $values = array();

    /**
     * Constructor.
     *
     * @param  string $template
     */
    public function __construct($template='',$openDelimiter = '{', $closeDelimiter = '}')
    {
        $this->setTemplate($template);
        $this->openDelimiter  = $openDelimiter;
        $this->closeDelimiter = $closeDelimiter;
    }

    /**
     * Call Constructor.
     *
     * @param  string $file
     */   
    public static function factory($template='',$openDelimiter = '{', $closeDelimiter = '}')
    {
        return new static($template,$openDelimiter, $closeDelimiter);
    }
    
    
    /**
     * Sets the template string.
     *
     * @param  string $file
     * @throws InvalidArgumentException
     */
     public function setTemplate($string)
     {
         $this->template=$string;
         
         return $this;
     }
     
        
    /**
     * Sets the template From file.
     *
     * @param  string $file
     * @throws InvalidArgumentException
     */
    public function setFromFile($file)
    {
        $distFile = $file . '.dist';

        if (file_exists($file)) {
            $this->template = file_get_contents($file);
        }

        else if (file_exists($distFile)) {
            $this->setTemplate(file_get_contents($distFile));
        }

        else {
            throw new InvalidArgumentException(
              'Template file could not be loaded.'
            );
        }
        
        return $this;
    }

    /**
     * Sets one or more template variables.
     *
     * @param  array   $values
     * @param  boolean $merge
     */
    public function setVars(array $values, $merge = TRUE)
    {
        if (!$merge || empty($this->values)) {
            $this->values = $values;
        } else {
            $this->values = array_merge($this->values, $values);
        }
        
        return $this;
    }
    

    /**
     * Add one template variables.
     *
     * @param  tag   $values
     * @param  value $merge
     */
    public function addVar($tag,$value)
    {
        return $this->setVars(array ( $tag => $value));
    }

    /**
     * Renders the template and returns the result. 
     *
     * @return string
     */
    public function render()
    {
        $keys = array();

        foreach ($this->values as $key => $value) {
            $keys[] = $this->openDelimiter . $key . $this->closeDelimiter;
        }

        return str_replace($keys, $this->values, $this->template);
    }
        
        
    
    public  function __toString()
    {
    }

    /**
     * Renders the template and writes the result to a file.
     *
     * @param string $target
     */
    public function renderToFile($target)
    {
        $fp = @fopen($target, 'wt');

        if ($fp) {
            fwrite($fp, $this->render());
            fclose($fp);
        } else {
            $error = error_get_last();

            throw new RuntimeException(
              sprintf(
                'Could not write to %s: %s',
                $target,
                substr(
                  $error['message'],
                  strpos($error['message'], ':') + 2
                )
              )
            );
        }
    }
}
