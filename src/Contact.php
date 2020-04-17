<?php
namespace Sicas;

use Sicas\Sicas;

class Contact extends Sicas
{
    const TYPE_DATA    = 'WS_Contactos';
    protected $catalog = "CatContactos";

    public function __construct(string $user, string $password)
    {
        parent::__construct($user, $password);
        $this->properties['PropertyTypeData'] = self::TYPE_DATA;
    }

}
