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

namespace i18nPlugin\ServiceManager;

use i18nPlugin\Languages\Languages;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguagesFactory implements FactoryInterface
{
    /**
     * Method to create a Language instance.
     * @param ServiceLocatorInterface $serviceLocator
     * @return \i18nPlugin\Languages\Languages
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        return new Languages($serviceLocator->get('Config'));
    }
}
