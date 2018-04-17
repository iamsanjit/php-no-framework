<?php declare(strict_types=1);

namespace App\Framework\Rendering;

interface TemplateRenderer
{
    public function render(String $template, array $data = []) : string;
}