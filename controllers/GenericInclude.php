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

class GenericInclude extends WebController {
    public function GET($file) {
        $prefix = $this->_request->getBaseUrl();
        $content = $this->_app->include_static("$file.html", compact('prefix'));
        if (!$content) {
            $this->_response->writeHead(404, array("Content-Type" => "application/xhtml+xml"));
            $this->_response->write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head></head><body><h1>Page not found!</h1></body></html>');
        } else {
            $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
            $this->_response->write($this->_app->template($content));
        }
        $this->_response->terminate();
    }
}
