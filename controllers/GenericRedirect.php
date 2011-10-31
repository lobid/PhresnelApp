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

class GenericRedirect extends WebController {
    public function GET($type, $id) {
        $url = $this->_request->getBaseUrl() . $this->_request->getUrl() . "/about";
        $this->_response->writeHead(303, array('Location' => $url));
        $this->_response->terminate();
    }

}
