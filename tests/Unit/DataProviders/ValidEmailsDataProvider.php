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
                        'name' => 'Fellipe Capelli'
                    ],
                    'to' => [
                        [
                            'email' => 'fellipe.capelli@outlook.com',
                            'name' => 'Fellipe C. Fregoneze'
                        ]
                    ],
                    'subject' => 'Mailjet Transactor 1',
                    'textPart' => 'Hello, text part 1',
                    'htmlPart' => 'Hello, HTML part 1',
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
                    'textPart' => 'Hello, text part 2',
                    'htmlPart' => 'Hello, HTML part 2',
                ]
            ]
        ];
    }
}
