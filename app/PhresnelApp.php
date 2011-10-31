<?php

/**
 * Copyright 2011 Felix Ostrowski, hbz
 *
 * This file is part of PhresnelApp.
 *
 * PhresnelApp is free software: you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * PhresnelApp is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License
 * for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with PhresnelApp.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class PhresnelApp extends WebApp {

    protected $_routes = array(
        '/:type/:id/about/edit' => 'GenericEdit',
        '/:type/:id/about' => 'GenericDisplay',
        '/:type/:id' => 'GenericRedirect',
        '/:type' => 'GenericList',
        );

    /**
     * TODO: short description.
     * 
     * @param  mixed  $content 
     * @return TODO
     */
    public function template($content) {
        $prefix = $this->_request->getBaseUrl();
        ob_start();
        include 'templates/index.html';
        return ob_get_clean();
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function dispatch() {
        $model = new LibRDF_Model(new LibRDF_Storage);
        $data = "";
        foreach (glob(dirname(__FILE__)."/../db/*.ttl") as $filename) {
            $data .= file_get_contents($filename);
        }
        $model->loadStatementsFromString(new LibRDF_Parser("turtle"), $data);
        $log = KLogger::instance("/tmp");
        Phresnel::setEndpoint(new localSPARQLEndpoint($model,$log));
        parent::dispatch($this->_routes);
    }

}
