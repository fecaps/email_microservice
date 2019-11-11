<?php

namespace Tests\Feature;

final class InvalidEmailsDataProvider
{
    public function invalidEmails(): array
    {
        $defaultNumberValue = 1;

        return [
            [
                [
                    'from' => [
                        'email' => $defaultNumberValue,
                        'name' => $defaultNumberValue
                    ],
                    'to' => [
                        'email' => $defaultNumberValue,
                        'name' => $defaultNumberValue
                    ],
                    'subject' => $defaultNumberValue,
                    'textPart' => $defaultNumberValue,
                    'htmlPart' => $defaultNumberValue,
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'invalid',
                        'name' => function () {
                        }
                    ],
                    'to' => [
                        'email' => function () {
                        },
                        'name' => str_repeat('a', 256)
                    ],
                    'subject' => -10,
                    'textPart' => new \DateTime(),
                    'htmlPart' => function () {
                    }
                ]
            ]
        ];
    }
}
