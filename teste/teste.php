<?php


use Moises\QueryBiulder\SelectQuery;
use Moises\QueryBiulder\InsertQuery;
use Moises\QueryBiulder\UpdateQuery;
use Moises\QueryBiulder\DeleteQuery;

require __DIR__ . '/../vendor/autoload.php';
$pdo = new PDO("pgsql:host=localhost;port=5432;dbname= 'alura_pdo'",'postgres','@postgres');
try {
  // passou
// $d = new DeleteQuery();
// $d->delete('clients', ['id'=>'10'],['=']);
// print_r($d->execute($pdo));
// echo PHP_EOL;

// passou
// $string = ' or 1=1; --';
// $i = new InsertQuery();
// $i->insert('clients', ['nome'])->values(['João']);
// $i->execute($pdo);
// echo PHP_EOL;

  // passou
  // $u = new UpdateQuery();
  // $u->update('clients')
  //   ->set(['nome' => "Artoria Pendragon"],['id'=>'7'],['=']);
  // print_r($u->execute($pdo));
  // echo PHP_EOL;

  // passou
  $s = new SelectQuery();
  $s->select(['nome','id_student'])
    ->from('student','s')
    // ->where(['nome'=>'maria,Jeanne'],['='],['or'])
  ->order(['nome'=>'DESC']);
  // ->join('INNER JOIN', 'phone', ['s.id_student','p.id_student'], '!=','p');
  $result = $s->execute($pdo)->fetchAll(PDO::FETCH_ASSOC);
  echo PHP_EOL;
  print_r($result);
  echo PHP_EOL;
} catch(\Exception $e){
  print_r("ERRO: {$e->getMessage()}");
}
