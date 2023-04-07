<?php

declare(strict_types=1);

namespace Core;

class Controller
{
    private Request $request;
    private Response $response;
    private View $view;

    public function getApplication(): Application
    {
        return Application::getInstance();
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }

    public function getView(): View
    {
        return $this->view;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
        $this->response->setHeader('Content-Type', 'text/html');
        $this->response->setStatusCode(200);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
