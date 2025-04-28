<?php

namespace App\Views;

class View
{
    protected string $layout = 'default';

    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    protected function getLayout(): string
    {
        ob_start();
        include ROOT_PATH . '/src/Views/layouts/_' . $this->layout . 'Layout.php';
        return ob_get_clean();
    }

    public function render(string $page, array $data = []): string
    {
        $layout = $this->getLayout();

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include ROOT_PATH . '/src/Views/pages/_' . $page . '.php';
        $content = ob_get_clean();

        $pageTitle = $pageTitle ?? 'Oglasi';
        $page = str_replace('{{CONTENT}}', $content, $layout);
        $page = str_replace('{{TITLE}}', $pageTitle, $page);
        return $page;
    }
}