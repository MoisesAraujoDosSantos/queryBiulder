<h1>Query biulder simples</h1> 

<p>Suporte apenas para PostgreSQL.</p>
<p>Para usar esse queryBiulder primeiramente deve fazer a conexão com o pdo</p>
<p>depois instanciar uma das Queries (Insert,Delete,Update ou Select)</p>
<p>Apos isso é só usar os metodos delas.</p>

<h2>Instalação</h2>

<p>composer require moises/query-biulder</p>

<h2>Classes e Metodos</h2>

<h3>Classe DeleteQuery :</h3>
<p>- delete() => deleta</p>
<p>- toSql() => retorna a querie sql formatada</p>

<h3>Classe InsertQuery:</h3>
<p>- insert() => insere o campo e o nome da tabela.</p>
<p>- values() => insere o valor que vai para o campo da tabela.</p>

<h3>Classe SelectQuery: </h3>
<p>- select() => seleciona as colunas.</p>
<p>- where() => opcional mas se colocado precisa de pelo menos uma condição.</p>
<p>- order() => opcional para a ordenação das colunas.</p>

<h3>Classe UpdateQuery:</h3>
<p>- update() => atualiza.</p>
<p>- set() => marca o atributo e o valor a ser atualizado</p>

<h3>Metodos Gerais: </h3>
<p>- metodo from() => pode ser usado com select e com update.</p>
<p>- execute() => metodo que efitivamente manda a querie para o banco.</p>

<h2>Orientações:</h2>
<p>- Utilizar os objetos englobados por um try catch para tratamento de exceções.</p>
<p>- O update não precisa de from para o nome de tabela, assim como é no PostgreSQL. Mas pode ser usado apos o update().</p>
<p>- Alguns metodos são inclusos dentro de outros para segurança. Como os metodos update() e delete() que fazem a chamada do metodo where internamente.</p>
<p>- O metodo set sempre vai atribuir valores ou seja, sempre retornará SET coluna = valor, o operador de comparação pedido no metodo é para a filtragem do where interior.</p>

<h2>Sintaxe:</h2>

<h3>Delete:</h3>
<pre><code>
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$d = new DeleteQuery();
$d->delete('nome_da_tabela', ['coluna'=>'valor'],['operador_de_comparação']);
$d->execute($pdo);
</code></pre>

<h3>Insert:</h3>
<pre><code>
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$i = new InsertQuery();
$i->insert('nome_da_tabela', ['colunas_a_serem_inseridas'])->values(['valores_a_serem_inseridos']);
$i->execute($pdo);
</code></pre>

<h3>Select:</h3> 
<pre><code>
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$s = new SelectQuery();
$s->select(['id', 'nome'])
  ->from('clients', 'c')
  ->where(['coluna'=>'valor_1,valor_2','coluna' => 'valor'],
          ['operador_de_comparação1,operador_de_comparação2'],
          ['operador_logico1,operador_logico2'])
  ->order(['coluna'=>'DESC']);
$result = $s->execute($pdo)->fetchAll(PDO::FETCH_ASSOC);
</code></pre>

<h3>Update:</h3>
<pre><code>
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$u = new UpdateQuery();
$u->update('tabela')
  ->set(['coluna' => "valor"],['condição'=>'valor'],['operador_de_comparação_where]);
$u->execute($pdo); 
</code></pre>
