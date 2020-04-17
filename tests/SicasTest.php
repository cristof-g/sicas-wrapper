<?php
use PHPUnit\Framework\TestCase;
use Sicas\Sicas;

final class SicasTest extends TestCase
{
    const EMPTY    = "";
    protected $sicas;

    public function testCanConnectedToSicasWebServices(): void
    {
        $this->sicas    = new Sicas($_ENV['user'], $_ENV['password']);
        $sicasFunctions = $this->sicas->getFunctions();

        $this->assertNotEmpty($sicasFunctions);
    }

    public function testIsCredentialsEmpty(): void
    {
        $this->sicas    = new Sicas(self::EMPTY, self::EMPTY);
        $sicasFunctions = $this->sicas->getFunctions();

        $this->assertEmpty($sicasFunctions);
    }

    public function testCanCreatedContact(): void
    {
        $this->sicas = new Sicas($_ENV['user'], $_ENV['password']);
        $this->sicas->setTypeData('WS_Contactos');
        $this->sicas->setCatalog('CatContactos');
        $this->sicas->setData([
            'TipoEnt'        => 'Fisica',
            'ApellidoP'      => 'Web',
            'ApellidoM'      => 'Services',
            'Nombre'         => 'Test',
            'FechaNac'       => '',
            'Sexo'           => 'Masculino',
            'Profesion'      => 'Tester',
            'Puesto'         => 'IT',
            'EdoCivil'       => 'Casado',
            'NombreCompleto' => 'Test Web Services',
            'Telefono1'      => 'Particular|9111111111',
            'EMail1'         => 'test@email.com',
            'Edad'           => '29',
        ]);

        $contact = $this->sicas->create();

        $this->assertNotEmpty($contact);
        $this->assertNotEmpty($contact['id']);
        $this->assertEquals('SUCESS', $contact['response']);
    }

    public function testCanCreatedClient(): void
    {
        $this->sicas = new Sicas($_ENV['user'], $_ENV['password']);
        $this->sicas->setTypeData('WS_Clientes');
        $this->sicas->setCatalog('CatClientes');
        $this->sicas->setData([
            'IDCli'    => '-1',
            'IDGrupo'  => '1',
            'IDEjecut' => '3',
            'IDCont'   =>  2828,
        ]);

        $client = $this->sicas->create();

        $this->assertNotEmpty($client);
        $this->assertNotEmpty($client['id']);
        $this->assertEquals('SUCESS', $client['response']);
    }
}
