<?php

if (! function_exists("bible"))
{
    function bible() {        
        return app()->make('laravel-bible');
    }
}