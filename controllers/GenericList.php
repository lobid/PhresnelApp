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

class GenericList extends WebController {
    public function GET($type) {
        $lens = Phresnel::getLens("{$type}List");
        $lens->loadResource(new LibRDF_BlankNode);
        $prefix = $this->_request->getBaseUrl();
        $formats = $this->_request->getHttpAccept();
        switch (key($formats)) {
            case 'application/rdf+xml':
                $this->_response->writeHead(200, array("Content-Type" => "application/rdf+xml"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("rdfxml-abbrev"));
                $this->_response->write($content);
                break;
            case 'application/x-turtle':
                $this->_response->writeHead(200, array("Content-Type" => "application/x-turtle"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("turtle"));
                $this->_response->write($content);
                break;
            case 'text/plain':
                $this->_response->writeHead(200, array("Content-Type" => "text/plain"));
                $data = $lens->getData();
                $content = $data->serializeStatements(new LibRDF_Serializer("ntriples"));
                $this->_response->write($content);
                break;
            default:
            case 'application/xhtml+xml':
                $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
                $renderer = new HTMLTableBoxModel($lens);
                $content = $renderer->render();
                $id = $this->__gen_uuid();
                $content .= "<a href=\"$prefix/$type/$id/about/edit\">add $type</a>";
                $this->_response->write($this->_app->template($content));
                break;
        }
        $this->_response->terminate();
    }

    private function __gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff )
                );
    }
}
