<?php
namespace Ipeweb\QueryBiulder;

require 'vendor/autoload.php';

$d = new DeleteQuery();
$d->delete('nome',['id = 1']);
print_r($d->toSql());

$i = new InsertQuery();
$i->insert('cliente',['nome'])->values('2');
print_r($i->toSql());

