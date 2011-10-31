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

class GenericDisplay extends WebController {
    public function GET($type, $id) {
        $lens = Phresnel::getLens($type);
        $base = $this->_request->getBaseUrl();
        $domain = $this->_request->getDomain();
        $uri = new LibRDF_URINode("http://$domain$base/$type/$id");
        $lens->loadResource($uri);
        $prefix = $this->_request->getBaseUrl();
        $formats = $this->_request->getHttpAccept();
        switch (key($formats)) {
            case 'application/rdf+xml':
                $this->_response->writeHead(200, array("Content-Type" => "application/rdf+xml"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("rdfxml-abbrev"));
                break;
            case 'application/x-turtle':
                $this->_response->writeHead(200, array("Content-Type" => "application/x-turtle"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("turtle"));
                break;
            case 'text/plain':
                $this->_response->writeHead(200, array("Content-Type" => "text/plain"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("ntriples"));
                break;
            default:
            case 'application/xhtml+xml':
                $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
                $renderer = new HTMLTableBoxModel($lens);
                $content = $renderer->render();
                $content .= "<a href=\"$prefix/$type/$id/about/edit\">edit</a>";
                $content .= " <a href=\"$prefix/$type\">$type list</a>";
                break;
        }
        $this->_response->write($this->_app->template($content));
        $this->_response->terminate();
    }

}
