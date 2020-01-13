<?php
declare(strict_types=1);

namespace App\DTO;

interface Email
{
    public function getId(): ?int;

    public function defineId($id): void;

    public function defineFrom($from): void;

    public function defineTo($to): void;

    public function defineSubject($subject): void;

    public function defineTextPart($textPart): void;

    public function defineHtmlPart($htmlPart): void;

    public function defineMarkdownPart($markdownPart): void;

    public function get(): array;
}
