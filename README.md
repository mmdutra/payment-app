# API de pagamento
API para realizar transaferências entre usuários.

## Tecnologias utilizadas :computer:
- Docker e Docker Compose
- PHP 7.4
- Lumen
- PHPUnit
- MySql
- Nginx

## Instalação e execução
Rodar o seguinte comando:
```
docker-compose up -d --build
```
Após isso, a aplicação está rodando na porta 8000 da sua máquina local.

## Decisões importantes
- Utilizar o micro-framework Lumen foi a melhor decisão, pois é uma ferramenta que já possuo maior familiariedade;
- O projeto foi desenvolvido utilizando TDD (Test-Drive Development), ou seja, escrevendo os testes de unidade antes de realizar a implementação de fato;
- Foram desenvolvidos testes de integração e de unidade, para ter maior cobertura e confiança no desenvolvimento e entrega do software;
- Banco de dados foi esclhido pois tenho mais familiariedade, além de que também seria um banco de dados que atenderia com certeza a demanda do serviço;

## Arquitetura
Foi seguido a arquitetura padrão do framework, porém separando a aplicação em contextos, exemplo:
```
  App\
    - Transaction\
    - User\
```
O objetivo desse modelo é isolar as diferentes "partes" do software de forma a deixar mais organizado, manutenível e limpo. 


## Documentação da API 
Para visualizar a documentação da API, acesse o endpoint ```/api/documentation```. 
