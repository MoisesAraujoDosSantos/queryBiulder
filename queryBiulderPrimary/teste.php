<?php
namespace Ipeweb\QueryBiulder;

use PDO;

require 'vendor/autoload.php';
$pdo = new PDO("pgsql:host=localhost;port=5432;dbname= 'alura_pdo'",'postgres','@postgres');

// $d = new DeleteQuery();
// $d->delete('clients', ['id = 5']);
// print_r($d->execute($pdo));
// echo PHP_EOL;

// passou
$string = ' or 1=1; --';
// $i = new InsertQuery();
// $i->insert('clients', ['nome'])->values(['ana']);
// $i->execute($pdo);
// echo PHP_EOL;

// passou
// $u = new UpdateQuery();
// $u->update('clients', ['id = 6'])
//   ->set(['nome'], ["Jeanne D'arc"]);
// $u->execute($pdo);
// echo PHP_EOL;


$s = new SelectQuery();
$s->select(['id', 'nome'])
  ->from('clients', 'c')
  ->where(['nome = Jeanne','nome = Artoria']);
  // ->order(1,['nome'],['DESC']);
$result = $s->execute($pdo)->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
echo PHP_EOL;

