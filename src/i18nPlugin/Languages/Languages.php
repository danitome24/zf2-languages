<?php
/**
 * i18nPlugin: Open plugin for using multilanguage on ZF2 applications
 * Copyright (C) 2015 SREd Servei de Recursos Educatius <http://www.sre.urv.cat/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
/**
 * @author Daniel Tomé <danieltomefer@gmail.com>
 * @copyright 2015 Servei de Recursos Educatius (http://www.sre.urv.cat)
 */

namespace i18nPlugin\Languages;

use Zend\Http\Header\SetCookie;

class Languages
{

    /**
     * 365 * 60 * 60 * 24
     */
    const TTL = 31536000;

    /**
     * @var string String with default language defined on module.config.php.
     */
    private $defaultLanguage;

    /**
     * @var array Array with all languages in intranet defined on module.config.php.
     */
    private $allLanguages;

    /**
     * @var array Array with codes of languages
     */
    private $languageCodes;

    /**
     * Construct method.
     * @param $config
     */
    public function __construct($config)
    {
        $this->defaultLanguage = trim(reset($config['languages']['default']));
        $this->allLanguages = array_map('trim', $config['languages']['all']);
        $this->languageCodes = array_map('trim', array_keys($this->allLanguages));

    }

    /**
     * Method to compare if $locale is default language.
     * @param $locale
     * @return bool
     */
    public function isDefaultLanguage($locale)
    {
        return ($locale === $this->defaultLanguage);
    }

    /**
     * Method to get all languages defined on module.config.php.
     * @return array
     */
    public function getLanguageCodes()
    {
        return $this->languageCodes;
    }

    /**
     * Method to get default language defined on module.config.php.
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * Method to translate a language
     * @param $lang Parameter must be the key of language e.g ('ca', 'es', 'en'..)
     * @return string Translation of language
     */
    public function translate($lang)
    {
        return (isset($this->allLanguages[$lang]))
            ? $this->allLanguages[$lang]
            : "";
    }

    /**
     * Method that returns if parameter $locale is a supported language
     * @param string $locale Locale to compare
     * @return bool True if is ok, and false if $locale is not supported
     */
    public function isValid($locale)
    {
        return isset($this->allLanguages[$locale]);
    }

    /**
     * Updating cookie if a different locale is set.
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @param string $locale
     */
    public function updateCookie($serviceManager, $locale)
    {
        $cookies = $serviceManager->get('Request')->getHeaders()->get('Cookie');
        if (!isset($cookies->locale) || $cookies->locale !== $locale) {
            $baseUrl = $serviceManager->get('Request')->getBaseUrl();
            $cookie = new SetCookie('locale', $locale, time() + self::TTL, $baseUrl);
            $response = $serviceManager->get('Response')->getHeaders();
            $response->addHeader($cookie);
        }
    }

    /**
     * This determines the locale for the current user session.
     * In the current implementation, we get it from an existing cookie, from browser defaults (if enabled) or from system configuration settings.
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager Service Manager instance
     * @return string
     */
    public function getLocaleForSession($serviceManager)
    {
        $config = $serviceManager->get('Config');
        $defaultLanguage = $this->getDefaultLanguage();
        //Cookie's part
        $cookies = $serviceManager->get('Request')->getHeaders()->get('Cookie');
        if (isset($cookies->locale) && $this->isValid($cookies->locale)) {
            return $cookies->locale;
        }
        // Taking most priorized language from browser
        if ($config['browser']['set_lang_from_browser'] && $serviceManager->get('Request')->getHeaders()->has('Accept-Language')) {
            $localesBrowser = $serviceManager->get('Request')->getHeaders()->get('Accept-Language')->getPrioritized();
            foreach ($localesBrowser as $localeBrowser) {
                $locale = substr($localeBrowser->getLanguage(), 0, 2);
                if ($this->isValid($locale)) {
                    $this->updateCookie($serviceManager, $locale);
                    return $locale;
                }
            }
        }
        //Default language from intranet
        $this->updateCookie($serviceManager, $defaultLanguage);
        return $defaultLanguage;
    }

    /*
     * Gets the list of languages supported in the system, of the form array('lang1' => 'Lang 1', ...)
     * @return array
     */
    public function getLanguages()
    {
        return $this->allLanguages;

    }
}
