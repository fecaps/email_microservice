<?php

namespace Tests\Unit\DataProviders;

final class ValidEmailsDataProvider
{
    public function emails(): array
    {
        return [
            [
                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli',
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze',
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 1',
                    'textPart' => 'Hello, text part 1',
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 2',
                    'htmlPart' => 'Hello, HTML part 2',
                ]
            ],
            [

                [
                    'from' => [
                        'email' => 'fellipecapelli@gmail.com',
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 3',
                    'markdownPart' => 'Hello, markdown part 3',
                ]
            ],
        ];
    }
}
