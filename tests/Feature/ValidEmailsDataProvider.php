<?php

namespace Tests\Feature;

final class ValidEmailsDataProvider
{
    public function emails(): array
    {
        return [
            [
                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'fellipe - from',
                    ],
                    'to' => [
                        [
                            'email' => 'fellipecapelli@gmail.com',
                            'name' => 'fellipe - to',
                        ]
                    ],
                    'subject' => 'hello - subject',
                    'textPart' => 'hello - text part'
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'fellipe - from',
                    ],
                    'to' => [
                        [
                            'email' => 'fellipecapelli@gmail.com',
                            'name' => 'fellipe - to',
                        ]
                    ],
                    'subject' => 'hello - subject',
                    'htmlPart' => 'hello - html part'
                ],
            ],
            [
                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'fellipe - from',
                    ],
                    'to' => [
                        [
                            'email' => 'fellipecapelli@gmail.com',
                            'name' => 'fellipe - to',
                        ]
                    ],
                    'subject' => 'hello - subject',
                    'markdownPart' => 'hello - markdown part'
                ],
            ],
        ];
    }
}
