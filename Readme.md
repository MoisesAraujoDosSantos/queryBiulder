Query biulder simples //

Suporte apenas para PostgreSQL.
Para usar esse queryBiulder primeiramente deve fazer a conexão com o pdo
depois instanciar uma das Queries (Insert,Delete,Update ou Select)
Apos isso é só usar os metodos delas.

Classe DeleteQuery :
- delete() => deleta
- toSql() => retorna a querie sql formatada

Classe InsertQuery:
- insert() => insere o campo e o nome da tabela.
- values() => insere o valor que vai para o campo da tabela.

Classe SelectQuery: 
- select() => seleciona as colunas.
- where() => opcional mas se colocado precisa de pelo menos uma condição.
- order() => opcional para a ordenação das colunas.

Classe UpdateQuery:
- update() => atualiza.
- set() => marca o atributo e o valor a ser atualizado

Metodos Gerais: 
- metodo from() => pode ser usado com select e com update.
- execute() => metodo que efitivamente manda a querie para o banco.


Orientações:
- É bom utilizar or objetos englobados por um try catch para tratamento de exceções.
- O update não precisa de from para o nome de tabela, assim como é no PostgreSQL.Mas pode ser usado apos o update().
- Alguns metodos são inclusos dentro de outros para segurança.Como os metodos update() e delete() que fazem a chamada do metodo where internamente.
- O metodo set sempre vai atribuir valores ou seja, sempre retornará SET coluna = valor, o operador de comparação pedido no metodo é para a filtragem do where interior.


Sintaxe:

Delete:
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$d = new DeleteQuery();
$d->delete('nome_da_tabela', ['coluna'=>'valor'],['operador_de_comparação']);
$d->execute($pdo);

Insert:
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$i = new InsertQuery();
$i->insert('nome_da_tabela', ['colunas_a_serem_inseridas'])->values(['valores_a_serem_inseridos']);
$i->execute($pdo);

Select: 
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$s = new SelectQuery();
$s->select(['id', 'nome'])
->from('clients', 'c')
->where(['coluna'=>'valor_1,valor_2','coluna' => 'valor'],['operador_de_comparação1,operador_de_comparação2'],['operador_logico1,operador_logico2'])
->order(['coluna'=>'DESC']);
$result = $s->execute($pdo)->fetchAll(PDO::FETCH_ASSOC);

Update:
$pdo = new PDO("pgsql:host=localhost;port=port;dbname= 'name'",'usuario','senha');
$u = new UpdateQuery();
$u->update('tabela')
->set(['coluna' => "valor"],['condição'=>'valor'],['operador_de_comparação_where]);
$u->execute($pdo);

