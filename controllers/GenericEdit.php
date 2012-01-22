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

class GenericEdit extends WebController {
    public function GET($type, $id) {
        $lens = Phresnel::getLens($type);
        $base = $this->_request->getBaseUrl();
        $domain = $this->_request->getDomain();
        $uri = new LibRDF_URINode("http://$domain$base/$type/$id");
        $lens->loadResource($uri);
        $prefix = $this->_request->getBaseUrl();

        $this->_response->writeHead(200, array("Content-Type" => "text/html"));
        foreach (array_keys($this->_request->getHttpAccept()) as $format) {
            switch ($format) {
                case 'application/rdf+xml':
                    $this->_response->writeHead(200, array("Content-Type" => "application/rdf+xml"));
                    $data = $lens->getData();
                    $content = $data->serializeStatements(new LibRDF_Serializer("rdfxml-abbrev"));
                    break;
                default:
                case 'application/xhtml+xml':
                    $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
                    $renderer = new HTMLTableFormBoxModel($lens);
                    $content = $renderer->render();
            }
        }
        $this->_response->write($this->_app->template($content));
        $this->_response->terminate();
    }

    public function POST($type, $id) {
        $prefix = $this->_request->getBaseUrl();
        $base = $this->_request->getBaseUrl();
        $domain = $this->_request->getDomain();
        $uri = new LibRDF_URINode("http://$domain$base/$type/$id");
        $model = AbstractFormBoxModel::handlePostData($_POST);
        $lens = Phresnel::getLens($type);
        $lens->loadResource($uri, $model);
        $renderer = new HTMLTableFormBoxModel($lens);

        if (isset($_POST['remove']) or isset($_POST['add'])) {
            $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
            $html = $renderer->render();
            $this->_response->write($this->_app->template($html));
            $this->_response->terminate();
        } else {
            $this->_response->writeHead(200, array("Content-Type" => "application/xhtml+xml"));
            switch ($_POST['format']) {
                case 'RDFa':
                    $renderer = new HTMLTableBoxModel($lens);
                    $content = $renderer->render();
                    $html = "<h1>RDFa</h1>";
                    break;
                case 'Turtle':
                    $data = $lens->getData();
                    $content = $data->serializeStatements(new LibRDF_Serializer("turtle"));
                    $html = "<h1>Turtle</h1>";
                    break;
                case 'Save':
                    $data = $lens->getData();
                    $content = $data->serializeStatements(new LibRDF_Serializer("turtle"));
                    $uri = new LibRDF_URINode("http://$domain$base/$type/$id");
                    $filename = dirname(dirname(__FILE__)).'/db/'.md5($uri).'.ttl';
                    file_put_contents($filename, $content);
                    $base = $this->_request->getBaseUrl();
                    $domain = $this->_request->getDomain();
                    $url = "$base/$type/$id/about";
                    $this->_response->writeHead(303, array('Location' => $url));
                    $this->_response->terminate();
                    break;
                case 'RDF/XML':
                default:
                    $data = $lens->getData();
                    $content = $data->serializeStatements(new LibRDF_Serializer("rdfxml-abbrev"));
                    $html = "<h1>RDF/XML</h1>";
            }
            $html .= "<textarea>";
            $html .= htmlspecialchars($content);
            $html .= "</textarea>";
            $html .= "<p><a href=\"$prefix/$type/$id/about\">back</a></p>";
            $this->_response->write($this->_app->template($html));
            $this->_response->terminate();
            return;
        }
    }
}
