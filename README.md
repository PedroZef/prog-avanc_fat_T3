# Projeto em PHP - prog-avanc_fat_T3

## Descrição

-   Projeto para o Curso de Desenvolvimento de Sistemas na FAT - Fundação ao Apoio à Tecnologia - SP

## Funcionalidades principais:

-   Cadastro, atualização, edição e exclusão de registros [CRUD]
-   Visualização de Filmes
-   Interface web amigável

Tecnologias utilizadas: PHP, MySQL, HTML, CSS, JavaScript.

## Pré-requisitos

-   PHP >= 8.0
-   MySQL
-   XAMPP instalado

## Instalação

1. **Clone o repositório**
    ```bash
    git clone https://github.com/seu-usuario/prog-avanc_fat_T3.git
    ```
2. **Copie a pasta para o XAMPP**

    - Mova a pasta clonada para o diretório `htdocs` do XAMPP (exemplo: `C:\xampp\htdocs\prog-avanc_fat_T3`).

3. **Configure o banco de dados**

    - Abra o XAMPP e inicie o Apache e o MySQL.
    - Acesse o phpMyAdmin pelo navegador: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
    - Clique em "Importar" e selecione o arquivo `.sql` localizado na pasta `/moviestar` do projeto.

4. **Configure o arquivo de conexão**
    - Se necessário, edite o arquivo de configuração do banco de dados (ex: `config.php`) com usuário, senha e nome do banco.

## Como usar

1. Inicie o Apache e o MySQL pelo painel do XAMPP.
2. No navegador, acesse: [http://localhost/prog-avanc_fat_T3](http://localhost/prog-avanc_fat_T3)
3. Utilize o sistema conforme as opções do menu.
4. Para dúvidas, consulte a documentação ou entre em contato.

## Estrutura do Projeto

-
-   `/dao(Data Access Object)` — acesso e manipulação dos dados no banco(CRUD)
-   `/models` — Armazena as classes das entendidades do Sistema
-   `/globals` — conexão com o banco de dados
-   `/img` — arquivos públicos (imagens, uploads)
-   `README.md` — Instruções e descrição do projeto
-   `db.php` — configuração do banco de dados

## Contribuição

1. Faça um fork do projeto
2. Crie uma branch: `git checkout -b minha-feature`
3. Faça suas alterações e commit: `git commit -m 'Minha contribuição'`
4. Envie para o seu fork: `git push origin minha-feature`
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
