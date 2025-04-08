<?php

namespace App\Http;

class Flash
{
    /**
     * Create flash  session
     * @param  string       $title
     * @param  string       $text
     * @param  string       $type
     * @param  string|null  $key
     * @return void
     */
    public function create($title, $text, $type, $key, $useLanguage=false)
    {
        if($useLanguage) {
            $title = $title;
            $text = getPhrase($text);
        }

        session()->flash($key, [
            'title'  =>$title,
            'text'   => $text,
            'type'   => $type,
        ]);
    }

    /**
     * Create a warning flash message
     * @param  string   $title
     * @param  string   $text
     * @return void
     */
    public  function warning($title, $text, $key = 'flash_message')
    {
        return $this->create($title, $text, 'warning', $key);
    }

    /**
     * Create an error flash message
     * @param  string   $title
     * @param  string   $text
     * @return void
     */
    public  function error($title, $text, $key = 'flash_message')
    {
        return $this->create($title, $text, 'error', $key);
    }

    /**
     * Create an error flash message
     * @param  string   $title
     * @param  string   $text
     * @return void
     */
    public function error_instruction($title, $text, $instruction_type)
    {
        session()->flash('flash_message', [
            'title'             => $title,
            'text'              => $text,
            'type'              => 'error_instruction',
            'instruction_type'  => $instruction_type
        ]);
    }

    /**
     * Create a warning flash message
     * @param string $title
     * @param string $text
     * @param string $key
     * @return void
     */
    public  function success($title, $text, $key = 'flash_message')
    {
        return $this->create($title, $text, 'success', $key);
    }

    /**
     * Create an info flash message
     * @param  string   $title
     * @param  string   $text
     * @return void
     */
    public  function info($title, $text, $key = 'flash_message')
    {
        return $this->create($title, $text, 'info', $key);
    }

    /**
     * Create an overlay flash message
     * @param  string   $title
     * @param  string   $text
     * @return void
     */
    public  function overlay($title, $text, $type = 'info')
    {
        return $this->create($title, $text, $type, 'flash_overlay');
    }

}
