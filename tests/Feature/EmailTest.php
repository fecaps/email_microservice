<?php

namespace Tests\Feature;

use Tests\TestCase;

final class EmailTest extends TestCase
{
    /**
     * Test invalid HTTP Method for emails resource
     *
     * @return void
     */
    public function testInvalidHttpMethodForEmailsResource(): void
    {
        $response = $this
            ->json('PUT', 'emails', []);

        $response->assertStatus(405)
            ->assertHeader(
                'Content-Type', 'application/json'
            )
            ->assertJsonStructure(
                [ 'message', 'errors' ], $response->json()
            )
            ->assertJson([
                'message' => 'Resource Not Allowed',
                'errors' => [
                    'http_method;http_headers' =>
                        'Resource not allowed for this HTTP method and HTTP headers'
                ]
            ], true);
    }

    /**
     * Test invalid route for emails resource
     *
     * @return void
     */
    public function testInvalidRouteForEmailsResource(): void
    {
        $response = $this
            ->json('PUT', 'emails2', []);

        $response->assertStatus(404)
            ->assertHeader(
                'Content-Type', 'application/json'
            )
            ->assertJsonStructure(
                [ 'message', 'errors' ], $response->json()
            )
            ->assertJson([
                'message' => 'Resource Not Found',
                'errors' => [
                    'http_headers;url' =>
                        'Resource not found for this HTTP headers and URL'
                ]
            ], true);
    }

    /**
     * Test invalid cases for email resource
     *
     * @dataProvider \Tests\Feature\InvalidEmailsDataProvider::invalidEmails()
     * @return void
     */
    public function testInvalidPayloadsForEmailsResource($emailData): void
    {
        $response = $this
            ->json('POST', 'emails', $emailData);

        $response->assertStatus(422)
            ->assertHeader(
                'Content-Type', 'application/json'
            )
            ->assertJsonStructure(
                [ 'message', 'errors' ], $response->json()
            )
            ->assertJsonCount(9, 'errors');
    }
}
