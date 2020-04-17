<?php
use PHPUnit\Framework\TestCase;
use Sicas\Client;

final class ClientTest extends TestCase
{
    public function testCanCreatedClient(): void
    {
        $client = new Client($_ENV['user'], $_ENV['password']);
        $client->setData([
            'IDCli'    => '-1',
            'IDGrupo'  => '1',
            'IDEjecut' => '3',
            'IDCont'   => 2828,
        ]);

        $response = $client->create();

        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response['id']);
        $this->assertEquals('SUCESS', $response['response']);
    }
}
