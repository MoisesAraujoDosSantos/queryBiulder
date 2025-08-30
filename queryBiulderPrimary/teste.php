<?php
namespace Ipeweb\QueryBiulder;

require 'vendor/autoload.php';

$d = new DeleteQuery();
$d->delete('cliente', ['id = 1']);
echo $d->toSql();
echo PHP_EOL;

$i = new InsertQuery();
$i->insert('cliente', ['nome'])->values(['João']);
echo $i->toSql();
echo PHP_EOL;

$u = new UpdateQuery();
$u->update('cliente', null)
  ->set(['nome'], ['Maria'])
  ->where(['id = 1']);
echo $u->toSql();
echo PHP_EOL;

$s = new SelectQuery();
$s->select(['id', 'nome'])
  ->from('cliente', 'c')
  ->where(['id = 1']);
echo $s->toSql();
echo PHP_EOL;

