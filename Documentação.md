# Relatório Consolidado - API Sistema de Registro de Ponto

## Informações do Projeto

**Nome do Projeto:** API Sistema de Registro de Ponto  
**Tema da API:** Gerenciamento de funcionários e registros de ponto eletrônico  
**Integrantes:** Paulo Roberto Ribeiro Junior 2213084 e Raphael Turino

## Endpoints Implementados

### 1. Funcionários

#### 1.1 Listar Todos os Funcionários
- **Método HTTP:** GET
- **Rota:** `/api/funcionarios`
- **Descrição:** Retorna lista completa de funcionários cadastrados
- **Exemplo de Resposta (200 OK):**
```json
[
  {
    "id": 1,
    "nome": "João Silva",
    "email": "joao.silva@empresa.com",
    "cargo": "Desenvolvedor",
    "departamento": "TI",
    "data_admissao": "2024-01-15"
  }
]
```

#### 1.2 Buscar Funcionário por ID
- **Método HTTP:** GET
- **Rota:** `/api/funcionarios/{id}`
- **Descrição:** Retorna dados específicos de um funcionário
- **Exemplo de Resposta (200 OK):**
```json
{
  "id": 1,
  "nome": "João Silva",
  "email": "joao.silva@empresa.com",
  "cargo": "Desenvolvedor",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}
```
- **Exemplo de Erro (404 Not Found):**
```json
{"erro": "Funcionário não encontrado"}
```

#### 1.3 Criar Novo Funcionário
- **Método HTTP:** POST
- **Rota:** `/api/funcionarios`
- **Descrição:** Cadastra um novo funcionário
- **Exemplo do Corpo da Requisição:**
```json
{
  "nome": "Maria Santos",
  "email": "maria.santos@empresa.com",
  "cargo": "Analista",
  "departamento": "RH",
  "data_admissao": "2024-02-20"
}
```
- **Exemplo de Resposta (201 Created):**
```json
{
  "id": 3,
  "nome": "Maria Santos",
  "email": "maria.santos@empresa.com",
  "cargo": "Analista",
  "departamento": "RH",
  "data_admissao": "2024-02-20"
}
```
- **Exemplo de Erro de Validação (400 Bad Request):**
```json
{
  "erros": {
    "nome": "Nome é obrigatório e deve ter pelo menos 2 caracteres",
    "email": "E-mail válido é obrigatório"
  }
}
```

#### 1.4 Atualizar Funcionário
- **Método HTTP:** PUT
- **Rota:** `/api/funcionarios/{id}`
- **Descrição:** Atualiza dados de um funcionário existente
- **Exemplo do Corpo da Requisição:**
```json
{
  "nome": "João Silva Santos",
  "email": "joao.santos@empresa.com",
  "cargo": "Desenvolvedor Senior",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}
```
- **Exemplo de Resposta (200 OK):**
```json
{
  "id": 1,
  "nome": "João Silva Santos",
  "email": "joao.santos@empresa.com",
  "cargo": "Desenvolvedor Senior",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}
```

#### 1.5 Excluir Funcionário
- **Método HTTP:** DELETE
- **Rota:** `/api/funcionarios/{id}`
- **Descrição:** Remove um funcionário e seus registros associados
- **Exemplo de Resposta (200 OK):**
```json
{"mensagem": "Funcionário excluído com sucesso"}
```

### 2. Registros de Ponto

#### 2.1 Listar Todos os Registros
- **Método HTTP:** GET
- **Rota:** `/api/registros`
- **Descrição:** Retorna lista de registros de ponto (com filtro opcional por funcionário)
- **Filtro Opcional:** `?funcionario_id=1`
- **Exemplo de Resposta (200 OK):**
```json
[
  {
    "id": 1,
    "funcionario_id": 1,
    "data": "2025-06-18",
    "hora": "08:00",
    "tipo": "entrada",
    "localizacao": "presencial",
    "observacao": ""
  }
]
```

#### 2.2 Buscar Registro por ID
- **Método HTTP:** GET
- **Rota:** `/api/registros/{id}`
- **Descrição:** Retorna dados específicos de um registro de ponto
- **Exemplo de Resposta (200 OK):**
```json
{
  "id": 1,
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "08:00",
  "tipo": "entrada",
  "localizacao": "presencial",
  "observacao": ""
}
```

#### 2.3 Criar Novo Registro
- **Método HTTP:** POST
- **Rota:** `/api/registros`
- **Descrição:** Registra um novo ponto
- **Exemplo do Corpo da Requisição:**
```json
{
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "17:00",
  "tipo": "saida",
  "localizacao": "presencial",
  "observacao": "Fim do expediente"
}
```
- **Exemplo de Resposta (201 Created):**
```json
{
  "id": 3,
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "17:00",
  "tipo": "saida",
  "localizacao": "presencial",
  "observacao": "Fim do expediente"
}
```
- **Exemplo de Erro de Validação (400 Bad Request):**
```json
{
  "erros": {
    "funcionario_id": "Funcionário não encontrado",
    "tipo": "Tipo de registro inválido. Valores aceitos: entrada, saida_almoco, retorno_almoco, saida"
  }
}
```

#### 2.4 Atualizar Registro
- **Método HTTP:** PUT
- **Rota:** `/api/registros/{id}`
- **Descrição:** Atualiza um registro de ponto existente
- **Exemplo do Corpo da Requisição:**
```json
{
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "08:15",
  "tipo": "entrada",
  "localizacao": "remoto",
  "observacao": "Entrada corrigida"
}
```

#### 2.5 Excluir Registro
- **Método HTTP:** DELETE
- **Rota:** `/api/registros/{id}`
- **Descrição:** Remove um registro de ponto
- **Exemplo de Resposta (200 OK):**
```json
{"mensagem": "Registro excluído com sucesso"}
```

## Organização do Código

### Principais Funções Criadas

1. **`gerarProximoId($array)`**
   - **Responsabilidade:** Gera ID único incremental para novos recursos
   - **Funcionamento:** Analisa o array fornecido, encontra o maior ID existente e retorna o próximo valor incremental
   - **Uso:** Utilizada tanto para funcionários quanto registros ao criar novos recursos

2. **`buscarFuncionarioPorId($id)`**
   - **Responsabilidade:** Localiza funcionário específico no array por ID
   - **Funcionamento:** Percorre o array `$_SESSION['funcionarios']` e retorna o funcionário correspondente
   - **Retorno:** Array do funcionário encontrado ou `null` se não encontrado

3. **`buscarRegistroPorId($id)`**
   - **Responsabilidade:** Localiza registro específico no array por ID
   - **Funcionamento:** Percorre o array `$_SESSION['registros']` e retorna o registro correspondente
   - **Retorno:** Array do registro encontrado ou `null` se não encontrado

4. **`validarDadosFuncionario($dados)`**
   - **Responsabilidade:** Valida campos obrigatórios e formatos para funcionários
   - **Funcionamento:** Verifica presença, formato e unicidade dos dados de funcionários
   - **Retorno:** Array de erros (vazio se dados válidos)

5. **`validarDadosRegistro($dados)`**
   - **Responsabilidade:** Valida campos obrigatórios e formatos para registros
   - **Funcionamento:** Verifica dados do registro e existência do funcionário associado
   - **Retorno:** Array de erros (vazio se dados válidos)

6. **`enviarRespostaJson($dados, $statusCode)`**
   - **Responsabilidade:** Padroniza envio de respostas JSON com status HTTP correto
   - **Funcionamento:** Define o código de status HTTP, configura o Content-Type e envia a resposta em JSON
   - **Benefício:** Garante encoding UTF-8 e encerra a execução adequadamente

### Implementação do Roteamento

O sistema de roteamento foi expandido para suportar:

- **Identificação do Método HTTP:** Utiliza `$_SERVER['REQUEST_METHOD']` para determinar GET, POST, PUT, DELETE
- **Parsing da URL:** Extrai e limpa partes da URL usando `parse_url()` e `explode()`
- **Roteamento por Recursos:** Identifica se a requisição é para `/funcionarios` ou `/registros`
- **Tratamento de Parâmetros:** Extrai IDs da URL para endpoints específicos (ex: `/funcionarios/1`)
- **Query Parameters:** Suporta filtros via `$_GET` (ex: `?funcionario_id=1`)

### Estrutura de Decisão

```php
// Exemplo da estrutura de roteamento implementada
if ($pathParts[1] === 'funcionarios') {
    switch ($method) {
        case 'GET': // Listar todos ou buscar por ID
        case 'POST': // Criar novo
        case 'PUT': // Atualizar existente
        case 'DELETE': // Excluir
    }
} elseif ($pathParts[1] === 'registros') {
    // Lógica similar para registros
}
```

## Persistência de Dados

**Implementação Utilizada:** Sistema de sessões PHP (`$_SESSION`)

### Vantagens da Abordagem Escolhida:
- **Persistência Durante a Sessão:** Dados permanecem disponíveis entre requisições HTTP para o mesmo usuário
- **Facilidade de Implementação:** Não requer configuração de banco de dados ou manipulação de arquivos
- **Ideal para Desenvolvimento:** Permite testes sequenciais das operações CRUD sem perda de dados

### Estrutura de Dados:
- **`$_SESSION['funcionarios']`:** Array que armazena todos os funcionários cadastrados
- **`$_SESSION['registros']`:** Array que armazena todos os registros de ponto

### Inicialização dos Dados:
O sistema verifica se os arrays de sessão existem. Se não existirem, são inicializados com dados mockados:

```php
if (!isset($_SESSION['funcionarios'])) {
    $_SESSION['funcionarios'] = [
        // Dados iniciais de funcionários
    ];
}
```

### Limitações:
- Dados são perdidos quando a sessão expira
- Cada usuário tem seu próprio conjunto de dados
- Não há persistência permanente

## Validação de Dados

### Regras de Validação para Funcionários:

1. **Nome:**
   - Obrigatório
   - Mínimo de 2 caracteres após trim()
   - Erro: "Nome é obrigatório e deve ter pelo menos 2 caracteres"

2. **E-mail:**
   - Obrigatório
   - Formato válido (validado com `filter_var()`)
   - Único no sistema (não pode haver emails duplicados)
   - Erro: "E-mail válido é obrigatório" ou "E-mail já cadastrado"

3. **Cargo:**
   - Obrigatório
   - Mínimo de 2 caracteres após trim()
   - Erro: "Cargo é obrigatório e deve ter pelo menos 2 caracteres"

4. **Departamento:**
   - Obrigatório
   - Erro: "Departamento é obrigatório"

5. **Data de Admissão:**
   - Obrigatória
   - Formato específico: YYYY-MM-DD
   - Validada com regex: `/^\d{4}-\d{2}-\d{2}$/`
   - Erro: "Data de admissão é obrigatória e deve estar no formato YYYY-MM-DD"

### Regras de Validação para Registros:

1. **Funcionário ID:**
   - Obrigatório
   - Deve ser numérico
   - Funcionário deve existir no sistema
   - Erro: "ID do funcionário é obrigatório e deve ser numérico" ou "Funcionário não encontrado"

2. **Data:**
   - Obrigatória
   - Formato: YYYY-MM-DD
   - Validada com regex: `/^\d{4}-\d{2}-\d{2}$/`
   - Erro: "Data é obrigatória e deve estar no formato YYYY-MM-DD"

3. **Hora:**
   - Obrigatória
   - Formato: HH:MM
   - Validada com regex: `/^\d{2}:\d{2}$/`
   - Erro: "Hora é obrigatória e deve estar no formato HH:MM"

4. **Tipo:**
   - Obrigatório
   - Valores aceitos: 'entrada', 'saida_almoco', 'retorno_almoco', 'saida'
   - Erro: "Tipo de registro inválido. Valores aceitos: entrada, saida_almoco, retorno_almoco, saida"

5. **Localização:**
   - Obrigatória
   - Valores aceitos: 'presencial', 'remoto'
   - Erro: "Localização inválida. Valores aceitos: presencial, remoto"

6. **Observação:**
   - Opcional
   - Quando fornecida, é aplicado trim() para remover espaços extras

### Tratamento de Erros:

**Estrutura de Retorno de Erros:**
- **Erro único:** `{"erro": "mensagem descritiva"}`
- **Múltiplos erros:** `{"erros": {"campo1": "mensagem1", "campo2": "mensagem2"}}`

**Códigos de Status HTTP Utilizados:**
- **200 OK:** Operação realizada com sucesso
- **201 Created:** Recurso criado com sucesso
- **400 Bad Request:** Dados inválidos ou erro de validação
- **404 Not Found:** Recurso não encontrado
- **405 Method Not Allowed:** Método HTTP não permitido para o endpoint

## Instruções Detalhadas de Teste

### Configuração do Ambiente:

1. **Requisitos:**
   - Servidor web com PHP habilitado (Apache, Nginx, ou servidor embutido do PHP)
   - PHP 7.0 ou superior

2. **Instalação:**
   - Colocar o arquivo `index.php` no diretório do servidor web
   - Certificar-se de que as sessões PHP estão habilitadas

3. **Inicialização:**
   - Acessar via browser: `http://localhost/caminho-para-api/index.php`
   - Ou usando servidor embutido: `php -S localhost:8000 index.php`

### Ferramentas de Teste Recomendadas:
- **Postman** (interface gráfica amigável)
- **Insomnia** (alternativa ao Postman)
- **cURL** (linha de comando)
- **Thunder Client** (extensão VS Code)

### Exemplos de Teste Detalhados:

#### 1. Teste de Criação de Funcionário (POST)

**Requisição de Sucesso:**
```
URL: http://localhost:8000/api/funcionarios
Method: POST
Headers: Content-Type: application/json
Body:
{
  "nome": "Carlos Silva",
  "email": "carlos.silva@empresa.com",
  "cargo": "Analista de Sistemas",
  "departamento": "TI",
  "data_admissao": "2025-06-18"
}

Resposta Esperada (201):
{
  "id": 3,
  "nome": "Carlos Silva",
  "email": "carlos.silva@empresa.com",
  "cargo": "Analista de Sistemas",
  "departamento": "TI",
  "data_admissao": "2025-06-18"
}
```

**Teste de Validação (Dados Inválidos):**
```
Body:
{
  "nome": "",
  "email": "email-invalido",
  "cargo": "A",
  "data_admissao": "2025/06/18"
}

Resposta Esperada (400):
{
  "erros": {
    "nome": "Nome é obrigatório e deve ter pelo menos 2 caracteres",
    "email": "E-mail válido é obrigatório",
    "cargo": "Cargo é obrigatório e deve ter pelo menos 2 caracteres",
    "departamento": "Departamento é obrigatório",
    "data_admissao": "Data de admissão é obrigatória e deve estar no formato YYYY-MM-DD"
  }
}
```

#### 2. Teste de Busca de Funcionário (GET)

**Buscar Funcionário Existente:**
```
URL: http://localhost:8000/api/funcionarios/1
Method: GET

Resposta Esperada (200):
{
  "id": 1,
  "nome": "João Silva",
  "email": "joao.silva@empresa.com",
  "cargo": "Desenvolvedor",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}
```

**Buscar Funcionário Não Existente:**
```
URL: http://localhost:8000/api/funcionarios/999
Method: GET

Resposta Esperada (404):
{"erro": "Funcionário não encontrado"}
```

#### 3. Teste de Atualização de Funcionário (PUT)

```
URL: http://localhost:8000/api/funcionarios/1
Method: PUT
Headers: Content-Type: application/json
Body:
{
  "nome": "João Silva Santos",
  "email": "joao.santos@empresa.com",
  "cargo": "Desenvolvedor Senior",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}

Resposta Esperada (200):
{
  "id": 1,
  "nome": "João Silva Santos",
  "email": "joao.santos@empresa.com",
  "cargo": "Desenvolvedor Senior",
  "departamento": "TI",
  "data_admissao": "2024-01-15"
}
```

#### 4. Teste de Exclusão de Funcionário (DELETE)

```
URL: http://localhost:8000/api/funcionarios/1
Method: DELETE

Resposta Esperada (200):
{"mensagem": "Funcionário excluído com sucesso"}
```

#### 5. Teste de Criação de Registro (POST)

```
URL: http://localhost:8000/api/registros
Method: POST
Headers: Content-Type: application/json
Body:
{
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "14:00",
  "tipo": "retorno_almoco",
  "localizacao": "presencial",
  "observacao": "Retorno do almoço"
}

Resposta Esperada (201):
{
  "id": 3,
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "14:00",
  "tipo": "retorno_almoco",
  "localizacao": "presencial",
  "observacao": "Retorno do almoço"
}
```

#### 6. Teste de Filtro de Registros

```
URL: http://localhost:8000/api/registros?funcionario_id=1
Method: GET

Resposta: Lista apenas registros do funcionário ID 1
```

### Sequência de Testes Recomendada:

1. **Listar funcionários iniciais** (GET /api/funcionarios)
2. **Criar um novo funcionário** (POST /api/funcionarios)
3. **Buscar o funcionário criado** (GET /api/funcionarios/{id})
4. **Atualizar o funcionário** (PUT /api/funcionarios/{id})
5. **Criar registros para o funcionário** (POST /api/registros)
6. **Listar registros do funcionário** (GET /api/registros?funcionario_id={id})
7. **Atualizar um registro** (PUT /api/registros/{id})
8. **Excluir um registro** (DELETE /api/registros/{id})
9. **Excluir o funcionário** (DELETE /api/funcionarios/{id})

### Testes de Casos de Erro:

1. **Métodos não permitidos:** Tentar usar PATCH em endpoints que só aceitam PUT
2. **Endpoints não existentes:** Acessar rotas que não existem
3. **JSON malformado:** Enviar dados com sintaxe JSON inválida
4. **Referências inválidas:** Criar registro para funcionário inexistente

## Considerações Finais

### Desafios Enfrentados:

1. **Roteamento Manual:** Implementar um sistema de roteamento robusto sem frameworks externos exigiu cuidado especial com parsing de URLs e tratamento de parâmetros.

2. **Validação Abrangente:** Criar validações que cobrissem todos os casos de uso possíveis, incluindo formatos específicos e regras de negócio.

3. **Consistência nas Respostas:** Padronizar o formato das respostas JSON e códigos de status HTTP para todos os endpoints.

4. **Gerenciamento de Estado:** Utilizar sessões PHP para simular persistência de dados mantendo a simplicidade do projeto.

### Aprendizados das Partes 2 e 3:

1. **Arquitetura de APIs:** Compreensão prática dos princípios REST e organização de endpoints por recursos.

2. **Manipulação de JSON:** Experiência com `json_decode()` e `json_encode()` para comunicação cliente-servidor.

3. **Validação de Dados:** Importância da validação tanto no frontend quanto no backend para garantir integridade dos dados.

4. **Códigos de Status HTTP:** Uso apropriado de códigos de status para comunicar o resultado das operações.

5. **Organização de Código:** Benefícios da modularização através de funções para manutenibilidade e reusabilidade.

### Possíveis Melhorias:

1. **Banco de Dados:** Migração para um sistema de banco de dados relacional (MySQL, PostgreSQL) para persistência real dos dados.

2. **Autenticação e Autorização:** Implementação de sistema de login e controle de acesso aos endpoints.

3. **Paginação:** Adição de paginação nas listagens para otimizar performance com grandes volumes de dados.

4. **Logs de Auditoria:** Sistema de logs para rastrear todas as operações realizadas na API.

5. **Validação de Horários:** Implementar regras de negócio para validar sequência lógica dos registros de ponto (entrada antes da saída, etc.).

6. **Relatórios:** Endpoints para gerar relatórios de frequência e horas trabalhadas.

7. **Testes Automatizados:** Implementação de testes unitários e de integração para garantir qualidade do código.

8. **Documentação Automática:** Integração com ferramentas como Swagger para documentação interativa da API.

9. **Rate Limiting:** Implementação de controle de taxa de requisições para prevenir abuso da API.

10. **Versionamento:** Sistema de versionamento da API para manter compatibilidade com versões anteriores.

### Conclusão:

Este projeto proporcionou uma experiência completa no desenvolvimento de APIs RESTful com PHP, cobrindo desde os conceitos básicos até implementações mais avançadas de validação e tratamento de erros. A estrutura modular do código e a organização clara dos endpoints criam uma base sólida para futuras expansões e melhorias do sistema.
