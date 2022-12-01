<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ControllerApiSistema extends ControllerApiBase {
   
    public function callPing(Request $request, Response $response, array $args) {
        $data = array("data" => date("Y-m-d H:i:s"));
        
        return $response->withJson($data, 200);
    }

}