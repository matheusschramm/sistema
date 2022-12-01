<?php

date_default_timezone_set('America/Maceio');

use \Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once "lib/slim/autoload.php";
require_once("core/Utils.php");

require_once("controllers/ControllerApiBase.php");
require_once("controllers/ControllerApiUsuario.php");
require_once("controllers/ControllerApiSistema.php");

class Routes {

    public function __construct()
    {
        $this->runApp();
    }

    /**
     * Executa o app para realizar a chamada de rotas
     *
     * @throws Throwable
     */
    protected function runApp()
    {
        $app = new \Slim\App($this->getConfigurationContainer());
        
        $app->add(function ($req, $res, $next) {
            $response = $next($req, $res);
            
            return $response
                // ->withHeader('Access-Control-Allow-Origin', 'https://atividades-senac-gelvazio.vercel.app')
                // Aceita todas as origens
                ->withHeader('Access-Control-Allow-Origin', '*')
                // Aceita somente os atributos headers desta lista abaixo
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, apikey')
                // Aceita apenas os metodos abaixo
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
        });
        
        // Agrupando rotas para adicionar o middleware em todas as rotas de uma só vez
        $app->group('', function () use ($app) {
            // Pagina inicial da api
            $app->get('/', ControllerApiBase::class . ':home');

            // Ping
            $app->get('/ping', ControllerApiBase::class . ':callPing');
            $app->post('/ping', ControllerApiBase::class . ':callPing');
            
            // Folhas de pagamento
            $app->get('/folha', ControllerApiFolhaPagamento::class . ':index');
            $app->get('/folhadetalhe/{codigofolha}', ControllerApiFolhaPagamento::class . ':detalhaFolha');
    
            // Cadastros - Usuarios
            $app->post('/login', ControllerApiUsuario::class . ':loginUsuario');
            $app->post('/users', ControllerApiUsuario::class . ':gravaUsuario');
            $app->delete('/users', ControllerApiUsuario::class . ':deleteUsuario');

            $app->get('/users', ControllerApiUsuario::class . ':getUsuario');
            
            $app->put('/updatepassword', ControllerApiUsuario::class . ':updatePassword');
            $app->post('/resetpassword', ControllerApiUsuario::class . ':resetPassword');
            
             // Pagina inicial da api
            //aula 01-12-2022 
             $app->get('/sistema', ControllerApiBase::class . ':callPing');
       

            })->add($this->getMiddlewares());
            
            
            $app->run();
    }

    /**
     * Retorna a configuração das rotas
     *
     * @return \Slim\Container
     */
    private function getConfigurationContainer()
    {
        // Configuração de erros
        $configuration = [
            'settings' => [
                'displayErrorDetails' => true,
                'determineRouteBeforeAppMiddleware' => true,
            ],
        ];
        $configurationContainer = new \Slim\Container($configuration);

        return $configurationContainer;
    }

    /**
     * Retorna os midlewares de validação de rotas
     *
     * @return Closure
     */
    private function getMiddlewares()
    {
        // Middlewares
        $Middlware = function (Request $request, Response $response, $next) {
            
            $headers = $request->getHeaders();

            $response = $next($request, $response);

            return $response;
        };

        return $Middlware;
    }    

}

$routes = new Routes();
