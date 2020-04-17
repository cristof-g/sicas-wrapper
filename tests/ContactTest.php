<?php
use PHPUnit\Framework\TestCase;
use Sicas\Contact;

final class ContactTest extends TestCase
{
    public function testCanCreatedContact(): void
    {
        $contact = new Contact($_ENV['user'], $_ENV['password']);
        $contact->setData([
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

        $response = $contact->create();

        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response['id']);
        $this->assertEquals('SUCESS', $response['response']);
    }
}
