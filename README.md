# 🕐 API de Controle de Ponto

Uma API REST simples para gerenciamento de funcionários e registros de ponto, desenvolvida em PHP puro com armazenamento em sessão.

## 📋 Funcionalidades

- ✅ **Gerenciamento de Funcionários**: CRUD completo (Create, Read, Update, Delete)
- ✅ **Registros de Ponto**: Controle de entrada, saída, almoço e trabalho remoto
- ✅ **Validação de Dados**: Validação robusta de entrada de dados
- ✅ **Filtros**: Busca de registros por funcionário
- ✅ **CORS**: Suporte para requisições cross-origin
- ✅ **JSON**: Todas as respostas em formato JSON

## 🚀 Instalação e Configuração

### Pré-requisitos
- PHP 7.4 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server
- Extensão PHP Session habilitada

### Instalação Rápida

1. **Clone ou baixe o arquivo da API**
```bash
# Baixe o arquivo api.php para seu servidor
```

2. **Configure o servidor web**

**Opção 1: Servidor built-in do PHP (desenvolvimento)**
```bash
php -S localhost:8000
```

**Opção 2: Apache com .htaccess**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ /api.php [QSA,L]
```

3. **Teste a instalação**
```bash
curl http://localhost:8000/api/funcionarios
```

## 📖 Documentação da API

### Base URL
```
http://localhost:8000/api
```

### Autenticação
Esta API não requer autenticação (adequado apenas para desenvolvimento/teste).

---

## 👥 Endpoints - Funcionários

### 📋 Listar Funcionários
```http
GET /api/funcionarios
```

**Resposta:**
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

### 🔍 Buscar Funcionário por ID
```http
GET /api/funcionarios/{id}
```

**Parâmetros:**
- `id` (integer): ID do funcionário

**Resposta (200):**
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

**Resposta (404):**
```json
{
  "erro": "Funcionário não encontrado"
}
```

### ➕ Criar Funcionário
```http
POST /api/funcionarios
Content-Type: application/json
```

**Body:**
```json
{
  "nome": "Maria Santos",
  "email": "maria.santos@empresa.com",
  "cargo": "Analista",
  "departamento": "RH",
  "data_admissao": "2025-06-18"
}
```

**Campos obrigatórios:**
- `nome` (string, min: 2 caracteres)
- `email` (string, formato de email válido)
- `cargo` (string, min: 2 caracteres)
- `departamento` (string)
- `data_admissao` (string, formato: YYYY-MM-DD)

**Resposta (201):**
```json
{
  "id": 3,
  "nome": "Maria Santos",
  "email": "maria.santos@empresa.com",
  "cargo": "Analista",
  "departamento": "RH",
  "data_admissao": "2025-06-18"
}
```

### ✏️ Atualizar Funcionário
```http
PUT /api/funcionarios/{id}
Content-Type: application/json
```

**Body:** (mesmo formato do POST)

### 🗑️ Excluir Funcionário
```http
DELETE /api/funcionarios/{id}
```

**Resposta (200):**
```json
{
  "mensagem": "Funcionário excluído com sucesso"
}
```

⚠️ **Atenção:** Excluir um funcionário também remove todos os seus registros de ponto.

---

## ⏰ Endpoints - Registros de Ponto

### 📋 Listar Registros
```http
GET /api/registros
```

**Parâmetros opcionais:**
- `funcionario_id` (integer): Filtrar registros por funcionário

**Exemplo:**
```http
GET /api/registros?funcionario_id=1
```

### 🔍 Buscar Registro por ID
```http
GET /api/registros/{id}
```

### ➕ Criar Registro
```http
POST /api/registros
Content-Type: application/json
```

**Body:**
```json
{
  "funcionario_id": 1,
  "data": "2025-06-18",
  "hora": "08:00",
  "tipo": "entrada",
  "localizacao": "presencial",
  "observacao": "Chegada normal"
}
```

**Campos obrigatórios:**
- `funcionario_id` (integer): ID do funcionário (deve existir)
- `data` (string, formato: YYYY-MM-DD)
- `hora` (string, formato: HH:MM)
- `tipo` (string): `entrada`, `saida_almoco`, `retorno_almoco`, `saida`
- `localizacao` (string): `presencial`, `remoto`

**Campos opcionais:**
- `observacao` (string): Observação sobre o registro

### ✏️ Atualizar Registro
```http
PUT /api/registros/{id}
Content-Type: application/json
```

### 🗑️ Excluir Registro
```http
DELETE /api/registros/{id}
```

---

## 📝 Exemplos de Uso

### Exemplo completo com cURL

```bash
# 1. Listar funcionários existentes
curl -X GET "http://localhost:8000/api/funcionarios"

# 2. Criar um novo funcionário
curl -X POST "http://localhost:8000/api/funcionarios" \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Pedro Oliveira",
    "email": "pedro.oliveira@empresa.com",
    "cargo": "Designer",
    "departamento": "Marketing",
    "data_admissao": "2025-06-18"
  }'

# 3. Registrar entrada do funcionário
curl -X POST "http://localhost:8000/api/registros" \
  -H "Content-Type: application/json" \
  -d '{
    "funcionario_id": 1,
    "data": "2025-06-18",
    "hora": "08:30",
    "tipo": "entrada",
    "localizacao": "presencial",
    "observacao": "Entrada matinal"
  }'

# 4. Buscar registros do funcionário
curl -X GET "http://localhost:8000/api/registros?funcionario_id=1"
```

### Exemplo com JavaScript (Frontend)

```javascript
// Função para criar um funcionário
async function criarFuncionario() {
  const response = await fetch('http://localhost:8000/api/funcionarios', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      nome: 'Ana Costa',
      email: 'ana.costa@empresa.com',
      cargo: 'Analista de Marketing',
      departamento: 'Marketing',
      data_admissao: '2025-06-18'
    })
  });
  
  const funcionario = await response.json();
  console.log(funcionario);
}

// Função para registrar ponto
async function registrarPonto(funcionarioId) {
  const agora = new Date();
  const data = agora.toISOString().split('T')[0];
  const hora = agora.toTimeString().slice(0, 5);
  
  const response = await fetch('http://localhost:8000/api/registros', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      funcionario_id: funcionarioId,
      data: data,
      hora: hora,
      tipo: 'entrada',
      localizacao: 'presencial'
    })
  });
  
  const registro = await response.json();
  console.log(registro);
}
```

---

## ❌ Códigos de Erro

| Código | Descrição | Exemplo |
|--------|-----------|---------|
| 200 | Sucesso | Operação realizada com sucesso |
| 201 | Criado | Recurso criado com sucesso |
| 400 | Bad Request | Dados inválidos ou faltando |
| 404 | Not Found | Recurso não encontrado |
| 405 | Method Not Allowed | Método HTTP não permitido |

### Exemplos de Respostas de Erro

**Dados inválidos (400):**
```json
{
  "erros": {
    "nome": "Nome é obrigatório e deve ter pelo menos 2 caracteres",
    "email": "E-mail válido é obrigatório"
  }
}
```

**Recurso não encontrado (404):**
```json
{
  "erro": "Funcionário não encontrado"
}
```

**E-mail duplicado (400):**
```json
{
  "erro": "E-mail já cadastrado"
}
```

---

## 🧪 Testes

### Interface Web de Testes
Uma interface HTML completa está disponível para testar todos os endpoints da API de forma visual e interativa.

### Dados de Teste Pré-carregados
A API vem com dados de exemplo:

**Funcionários:**
- João Silva (ID: 1) - Desenvolvedor/TI
- Maria Santos (ID: 2) - Analista/RH

**Registros:**
- Entrada de João Silva às 08:00
- Saída para almoço às 12:00

### Testes Automatizados
Para implementar testes automatizados, recomenda-se usar PHPUnit:

```bash
composer require --dev phpunit/phpunit
```

---

## 🔧 Configuração Adicional

### Configuração de CORS
A API já está configurada para aceitar requisições de qualquer origem. Para produção, configure adequadamente:

```php
header('Access-Control-Allow-Origin: https://seudominio.com');
```

### Configuração de Timezone
Para garantir horários corretos, configure o timezone:

```php
date_default_timezone_set('America/Sao_Paulo');
```

### Configuração de Logs
Para ambiente de produção, implemente logs:

```php
error_log("API Error: " . $message, 3, "/var/log/api_errors.log");
```

---

## 📊 Estrutura de Dados

### Schema do Funcionário
```json
{
  "id": "integer (auto-increment)",
  "nome": "string (required, min: 2)",
  "email": "string (required, unique, valid email)",
  "cargo": "string (required, min: 2)",
  "departamento": "string (required)",
  "data_admissao": "string (required, format: YYYY-MM-DD)"
}
```

### Schema do Registro
```json
{
  "id": "integer (auto-increment)",
  "funcionario_id": "integer (required, foreign key)",
  "data": "string (required, format: YYYY-MM-DD)",
  "hora": "string (required, format: HH:MM)",
  "tipo": "enum (required: entrada|saida_almoco|retorno_almoco|saida)",
  "localizacao": "enum (required: presencial|remoto)",
  "observacao": "string (optional)"
}
```

---

## ⚠️ Limitações

- **Armazenamento em Sessão**: Os dados são perdidos quando a sessão expira
- **Sem Autenticação**: Adequado apenas para desenvolvimento/teste
- **Sem Persistência**: Dados não são salvos em banco de dados
- **Sem Paginação**: Todos os registros são retornados de uma vez
- **Sem Rate Limiting**: Não há controle de limite de requisições

---

## 🔮 Próximos Passos

Para uso em produção, considere implementar:

- [ ] Banco de dados (MySQL, PostgreSQL)
- [ ] Sistema de autenticação (JWT, OAuth)
- [ ] Paginação de resultados
- [ ] Rate limiting
- [ ] Logs estruturados
- [ ] Testes automatizados
- [ ] Documentação OpenAPI/Swagger
- [ ] Validação de roles/permissões
- [ ] Backup e recuperação
- [ ] Monitoramento e métricas

---

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

## 📞 Suporte

Para dúvidas ou problemas:
- Abra uma issue no repositório
- Entre em contato com a equipe de desenvolvimento

---

**Desenvolvido com ❤️ para facilitar o controle de ponto empresarial**