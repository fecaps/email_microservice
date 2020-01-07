<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;
use App\Enum\Email;
use App\DTO\EmailDTO;

class EmailDTOTest extends TestCase
{
    /**
     * Define ID Test With Null
     *
     * @return void
     */
    public function testDefineIdWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineId(null);
        $id = $emailDTO->getId();

        $this->assertNull($id);
    }

    /**
     * Define ID Test With Int
     *
     * @return void
     */
    public function testDefineIdWithInt(): void
    {
        $value = 1;

        $emailDTO = new EmailDTO();
        $emailDTO->defineId($value);
        $id = $emailDTO->getId();

        $this->assertSame($value, $id);
    }

    /**
     * Define From Test With Null
     *
     * @return void
     */
    public function testDefineFromWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineFrom(null);
        $email = $emailDTO->get();

        $this->assertEquals([], $email[Email::FROM_KEY]);
    }

    /**
     * Define From Test With Array
     *
     * @return void
     */
    public function testDefineFromWithArray(): void
    {
        $value = [
            'name' => 'fellipe',
            'email' => 'fellipe@gmail.com',
        ];

        $emailDTO = new EmailDTO();
        $emailDTO->defineFrom($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::FROM_KEY]);
    }

    /**
     * Define To Test With Null
     *
     * @return void
     */
    public function testDefineToWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineTo(null);
        $email = $emailDTO->get();

        $this->assertEquals([], $email[Email::TO_KEY]);
    }

    /**
     * Define To Test With Array
     *
     * @return void
     */
    public function testDefineToWithArray(): void
    {
        $value = [
            [
                'name' => 'fellipe',
                'email' => 'fellipe@gmail.com',
            ],
        ];

        $emailDTO = new EmailDTO();
        $emailDTO->defineTo($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::TO_KEY]);
    }

    /**
     * Define Subject Test With Null
     *
     * @return void
     */
    public function testDefineSubjectWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineSubject(null);
        $email = $emailDTO->get();

        $this->assertNull($email[Email::SUBJECT_KEY]);
    }

    /**
     * Define Subject Test With Array
     *
     * @return void
     */
    public function testDefineSubjectWithString(): void
    {
        $value = 'subject';

        $emailDTO = new EmailDTO();
        $emailDTO->defineSubject($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::SUBJECT_KEY]);
    }

    /**
     * Define TextPart Test With Null
     *
     * @return void
     */
    public function testDefineTextPartWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineTextPart(null);
        $email = $emailDTO->get();

        $this->assertArrayNotHasKey(Email::TEXT_PART_KEY, $email);
    }

    /**
     * Define TextPart Test With Array
     *
     * @return void
     */
    public function testDefineTextPartWithString(): void
    {
        $value = 'text part';

        $emailDTO = new EmailDTO();
        $emailDTO->defineTextPart($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::TEXT_PART_KEY]);
    }

    /**
     * Define HtmlPart Test With Null
     *
     * @return void
     */
    public function testDefineHtmlPartWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineHtmlPart(null);
        $email = $emailDTO->get();

        $this->assertArrayNotHasKey(Email::HTML_PART_KEY, $email);
    }

    /**
     * Define HtmlPart Test With Array
     *
     * @return void
     */
    public function testDefineHtmlPartWithString(): void
    {
        $value = 'html part';

        $emailDTO = new EmailDTO();
        $emailDTO->defineHtmlPart($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::HTML_PART_KEY]);
    }

    /**
     * Define MarkdownPart Test With Null
     *
     * @return void
     */
    public function testDefineMarkdownPartWithNull(): void
    {
        $emailDTO = new EmailDTO();
        $emailDTO->defineMarkdownPart(null);
        $email = $emailDTO->get();

        $this->assertArrayNotHasKey(Email::MARKDOWN_PART_KEY, $email);
    }

    /**
     * Define MarkdownPart Test With Array
     *
     * @return void
     */
    public function testDefineMarkdownPartWithString(): void
    {
        $value = 'html part';

        $emailDTO = new EmailDTO();
        $emailDTO->defineMarkdownPart($value);
        $email = $emailDTO->get();

        $this->assertSame($value, $email[Email::MARKDOWN_PART_KEY]);
    }
}
