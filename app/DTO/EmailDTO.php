<?php
declare(strict_types=1);

namespace App\DTO;

use App\Enum\Email as EmailEnum;

class EmailDTO implements Email
{
    private $id;
    private $from = [];
    private $to = [];
    private $subject;
    private $textPart;
    private $htmlPart;
    private $markdownPart;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function defineId($id): void
    {
        if ($id === null || !is_int($id)) {
            return;
        }

        $this->id = $id;
    }

    /**
     * @param array $from
     * @return void
     */
    public function defineFrom($from): void
    {
        if ($from === null || !is_array($from)) {
            return;
        }

        $this->from = $from;
    }

    /**
     * @param array $to
     * @return void
     */
    public function defineTo($to): void
    {
        if ($to === null || !is_array($to)) {
            return;
        }

        $this->to = array_merge($this->to, $to);
    }

    /**
     * @param string $subject
     * @return void
     */
    public function defineSubject($subject): void
    {
        if ($subject === null || !is_string($subject)) {
            return;
        }

        $this->subject = $subject;
    }

    /**
     * @param string $textPart
     * @return void
     */
    public function defineTextPart($textPart): void
    {
        if ($textPart === null || !is_string($textPart)) {
            return;
        }

        $this->textPart = $textPart;
    }

    /**
     * @param string $htmlPart
     * @return void
     */
    public function defineHtmlPart($htmlPart): void
    {
        if ($htmlPart === null || !is_string($htmlPart)) {
            return;
        }

        $this->htmlPart = $htmlPart;
    }

    /**
     * @param string $markdownPart
     * @return void
     */
    public function defineMarkdownPart($markdownPart): void
    {
        if ($markdownPart === null || !is_string($markdownPart)) {
            return;
        }

        $this->markdownPart = $markdownPart;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $email = [
            EmailEnum::FROM_KEY => $this->from,
            EmailEnum::TO_KEY => $this->to,
            EmailEnum::SUBJECT_KEY => $this->subject,
        ];

        $email = $this->defineValueOnPath($this->textPart, EmailEnum::TEXT_PART_KEY, $email);
        $email = $this->defineValueOnPath($this->htmlPart, EmailEnum::HTML_PART_KEY, $email);
        $email = $this->defineValueOnPath($this->markdownPart, EmailEnum::MARKDOWN_PART_KEY, $email);

        return $this->defineValueOnPath($this->id, EmailEnum::ID_KEY, $email);
    }

    private function defineValueOnPath($value, string $path, array $email): array
    {
        if ($value !== null) {
            $email[$path] = $value;
        }

        return $email;
    }
}
