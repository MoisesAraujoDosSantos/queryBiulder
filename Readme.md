Query biulder simples //
para usar esse queryBiulder primeiramente deve fazer a conexão com o pdo
depois instanciar uma das Queries (Insert,Delete,Update ou Select)
Apos isso é só usar os metodos delas
Delete :
- delete() => deleta
- toSql() => retorna a querie sql formatada
Insert:
- insert() => insere o campo e o nome da tabela
- values() => insere o valor que vai para o campo da tabela

Select: 
- select() => seleciona as colunas
- where() => opcional mas se colocado precisa de pelo menos uma condição