Introduction:
-------------

This plugin help us using multilanguage on our Zend Framework 2 application. It provides us
an API and a config. This config will be fundamental for correct working.


Installation:
-------------

To install this module you must follow this steps:

1 - In your `composer.json` you must write:

```php
"require": {
        "sred/i18nplugin" : "dev-master"
    },
```
    and

```php
"autoload": {
      "psr-4":{
         "i18nplugin\\" : "vendor/sred/i18nplugin"
      }
}
```

2 - Then you must run the following command line: `composer update`

3 - Also run the command `composer dump-autoload`.

4 - And the last one step is adding the module to your `application.conf.php`
```php
    'modules' => array(
        'i18nplugin',
    ),
```

Configuration:
--------------
To use this plugin you must make a config like the following:

```php
'languages' => array(
        'all' => array(
            'ca' => 'Catalan',
            'es' => 'Spanish',
            'en' => 'English',
        ),
        'default' => array(
            'ca',
        ),
    ),
```

```php
'browser' => array(
        'set_lang_from_browser' => true,
    ),
```

In `all` array you must have the key and the translation of this key. For example: 'en' => 'English'.
In `default` language you must have the key of the default language.
In `browser` array you must have defined if you want to get language from browser configuration or not.



Usage:
------

It is very easy using this plugin. We provide a Helper and a Plugin so in the view or controllers you only must write :
`$this->languages()->method()`.

If you want to use it out of view and controllers, we provide a Factory of Languages. So you only must write:

`$languages = $serviceManager->get('languages');` and you'll have all the functions available.




API provided:
-------------
`isDefaultLanguage($lang)` -> This method will return if parameter is equals to default language defined on config(key).

`getDefaultLanguage()` -> This method will return the default language defined on our config file.

`getLanguagesCodes()` -> This method will return the array defined on our config with the key 'all'. (keys and translations).

`translate($lang)` -> Given a key of a language, this method will return the translation of that key.

`isValid($lang)` -> Given a key this method will return if this language is defined or not.

`updateCookie($serviceManager, $lang)` -> This method will help us to create a cookie with language defined on our application.

`getLocaleForSession($serviceManager)` -> This method will give to us the language depending on the language on our browser or cookie.
