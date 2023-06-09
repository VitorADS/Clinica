<?php

namespace App\Http;

use App\Models\Services\AbstractService;
use Config\EntityManager\EntityManagerCreator;
use Doctrine\ORM\EntityManager;

class Request{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $postVars = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var User
     */
    public $user;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var AbstractService
     */
    private $service;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->em = EntityManagerCreator::getEntityManager();
        $this->setUri();
        $this->setPostVars();
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService(string $entity)
    {
        $this->service = new AbstractService($this->em, $entity);
    }

    /**
     * 
     */
    private function setPostVars()
    {
        if($this->httpMethod == 'GET') return false;
        $this->postVars = $_POST ?? [];

        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * 
     */
    private function setUri()
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return Router 
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }
}