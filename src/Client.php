<?php
namespace Sicas;

use Sicas\Sicas;

class Client extends Sicas
{
    const TYPE_DATA    = 'WS_Clientes';
    protected $catalog = "CatClientes";

    public function __construct(string $user, string $password)
    {
        parent::__construct($user, $password);
        $this->properties['PropertyTypeData'] = self::TYPE_DATA;
    }
   
}
