<?php

return [

    /*
     * Define the name used when automatically naming transactions.
     * a token string:
     *      a pattern you define yourself, available tokens:
     *          {controller} = Controller@action or Closure@path
     *          {method} = GET / POST / etc.
     *          {route} = route name if named, otherwise same as {controller}
     *          {path} = the registered route path (includes variable names)
     *          {uri} = the actual URI requested
     *      anything that is not a matched token will remain a string literal
     *      example:
     *          "GET /world" with pattern 'hello {path} you really {method} me' would return:
     *          'hello /world you really GET me'
     */
    'name_provider' => '{controller}',

];
