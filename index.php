<?php
// Configurações de CORS e Content-Type
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Tratamento para requisições OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inicialização dos dados em memória (simulando banco de dados)
session_start();

// Inicializar arrays se não existirem
if (!isset($_SESSION['funcionarios'])) {
    $_SESSION['funcionarios'] = [
        [
            'id' => 1,
            'nome' => 'João Silva',
            'email' => 'joao.silva@empresa.com',
            'cargo' => 'Desenvolvedor',
            'departamento' => 'TI',
            'data_admissao' => '2024-01-15'
        ],
        [
            'id' => 2,
            'nome' => 'Maria Santos',
            'email' => 'maria.santos@empresa.com',
            'cargo' => 'Analista',
            'departamento' => 'RH',
            'data_admissao' => '2024-02-20'
        ]
    ];
}

if (!isset($_SESSION['registros'])) {
    $_SESSION['registros'] = [
        [
            'id' => 1,
            'funcionario_id' => 1,
            'data' => '2025-06-18',
            'hora' => '08:00',
            'tipo' => 'entrada',
            'localizacao' => 'presencial',
            'observacao' => ''
        ],
        [
            'id' => 2,
            'funcionario_id' => 1,
            'data' => '2025-06-18',
            'hora' => '12:00',
            'tipo' => 'saida_almoco',
            'localizacao' => 'presencial',
            'observacao' => ''
        ]
    ];
}

// Funções auxiliares
function gerarProximoId($array) {
    if (empty($array)) {
        return 1;
    }
    $maxId = max(array_column($array, 'id'));
    return $maxId + 1;
}

function buscarFuncionarioPorId($id) {
    foreach ($_SESSION['funcionarios'] as $funcionario) {
        if ($funcionario['id'] == $id) {
            return $funcionario;
        }
    }
    return null;
}

function buscarRegistroPorId($id) {
    foreach ($_SESSION['registros'] as $registro) {
        if ($registro['id'] == $id) {
            return $registro;
        }
    }
    return null;
}

function validarDadosFuncionario($dados) {
    $erros = [];
    
    if (empty($dados['nome']) || strlen(trim($dados['nome'])) < 2) {
        $erros['nome'] = 'Nome é obrigatório e deve ter pelo menos 2 caracteres';
    }
    
    if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'E-mail válido é obrigatório';
    }
    
    if (empty($dados['cargo']) || strlen(trim($dados['cargo'])) < 2) {
        $erros['cargo'] = 'Cargo é obrigatório e deve ter pelo menos 2 caracteres';
    }
    
    if (empty($dados['departamento'])) {
        $erros['departamento'] = 'Departamento é obrigatório';
    }
    
    if (empty($dados['data_admissao']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['data_admissao'])) {
        $erros['data_admissao'] = 'Data de admissão é obrigatória e deve estar no formato YYYY-MM-DD';
    }
    
    return $erros;
}

function validarDadosRegistro($dados) {
    $erros = [];
    
    if (empty($dados['funcionario_id']) || !is_numeric($dados['funcionario_id'])) {
        $erros['funcionario_id'] = 'ID do funcionário é obrigatório e deve ser numérico';
    } else {
        $funcionario = buscarFuncionarioPorId($dados['funcionario_id']);
        if (!$funcionario) {
            $erros['funcionario_id'] = 'Funcionário não encontrado';
        }
    }
    
    if (empty($dados['data']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['data'])) {
        $erros['data'] = 'Data é obrigatória e deve estar no formato YYYY-MM-DD';
    }
    
    if (empty($dados['hora']) || !preg_match('/^\d{2}:\d{2}$/', $dados['hora'])) {
        $erros['hora'] = 'Hora é obrigatória e deve estar no formato HH:MM';
    }
    
    $tiposValidos = ['entrada', 'saida_almoco', 'retorno_almoco', 'saida'];
    if (empty($dados['tipo']) || !in_array($dados['tipo'], $tiposValidos)) {
        $erros['tipo'] = 'Tipo de registro inválido. Valores aceitos: entrada, saida_almoco, retorno_almoco, saida';
    }
    
    $localizacoesValidas = ['presencial', 'remoto'];
    if (empty($dados['localizacao']) || !in_array($dados['localizacao'], $localizacoesValidas)) {
        $erros['localizacao'] = 'Localização inválida. Valores aceitos: presencial, remoto';
    }
    
    return $erros;
}

function enviarRespostaJson($dados, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit();
}

// Roteamento
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Remover partes vazias do path
$pathParts = array_filter($pathParts);
$pathParts = array_values($pathParts);

// Roteamento para funcionários
if (isset($pathParts[1]) && $pathParts[1] === 'funcionarios') {
    
    switch ($method) {
        case 'GET':
            if (isset($pathParts[2]) && is_numeric($pathParts[2])) {
                // GET /api/funcionarios/{id}
                $id = intval($pathParts[2]);
                $funcionario = buscarFuncionarioPorId($id);
                
                if ($funcionario) {
                    enviarRespostaJson($funcionario);
                } else {
                    enviarRespostaJson(['erro' => 'Funcionário não encontrado'], 404);
                }
            } else {
                // GET /api/funcionarios
                enviarRespostaJson($_SESSION['funcionarios']);
            }
            break;
            
        case 'POST':
            // POST /api/funcionarios
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                enviarRespostaJson(['erro' => 'Dados JSON inválidos'], 400);
            }
            
            $erros = validarDadosFuncionario($input);
            if (!empty($erros)) {
                enviarRespostaJson(['erros' => $erros], 400);
            }
            
            // Verificar se email já existe
            foreach ($_SESSION['funcionarios'] as $func) {
                if ($func['email'] === $input['email']) {
                    enviarRespostaJson(['erro' => 'E-mail já cadastrado'], 400);
                }
            }
            
            $novoFuncionario = [
                'id' => gerarProximoId($_SESSION['funcionarios']),
                'nome' => trim($input['nome']),
                'email' => trim($input['email']),
                'cargo' => trim($input['cargo']),
                'departamento' => $input['departamento'],
                'data_admissao' => $input['data_admissao']
            ];
            
            $_SESSION['funcionarios'][] = $novoFuncionario;
            enviarRespostaJson($novoFuncionario, 201);
            break;
            
        case 'PUT':
            // PUT /api/funcionarios/{id}
            if (!isset($pathParts[2]) || !is_numeric($pathParts[2])) {
                enviarRespostaJson(['erro' => 'ID do funcionário é obrigatório'], 400);
            }
            
            $id = intval($pathParts[2]);
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                enviarRespostaJson(['erro' => 'Dados JSON inválidos'], 400);
            }
            
            $funcionarioIndex = null;
            foreach ($_SESSION['funcionarios'] as $index => $func) {
                if ($func['id'] == $id) {
                    $funcionarioIndex = $index;
                    break;
                }
            }
            
            if ($funcionarioIndex === null) {
                enviarRespostaJson(['erro' => 'Funcionário não encontrado'], 404);
            }
            
            $erros = validarDadosFuncionario($input);
            if (!empty($erros)) {
                enviarRespostaJson(['erros' => $erros], 400);
            }
            
            // Verificar se email já existe (exceto para o próprio funcionário)
            foreach ($_SESSION['funcionarios'] as $func) {
                if ($func['email'] === $input['email'] && $func['id'] != $id) {
                    enviarRespostaJson(['erro' => 'E-mail já cadastrado'], 400);
                }
            }
            
            $_SESSION['funcionarios'][$funcionarioIndex] = [
                'id' => $id,
                'nome' => trim($input['nome']),
                'email' => trim($input['email']),
                'cargo' => trim($input['cargo']),
                'departamento' => $input['departamento'],
                'data_admissao' => $input['data_admissao']
            ];
            
            enviarRespostaJson($_SESSION['funcionarios'][$funcionarioIndex]);
            break;
            
        case 'DELETE':
            // DELETE /api/funcionarios/{id}
            if (!isset($pathParts[2]) || !is_numeric($pathParts[2])) {
                enviarRespostaJson(['erro' => 'ID do funcionário é obrigatório'], 400);
            }
            
            $id = intval($pathParts[2]);
            $funcionarioIndex = null;
            
            foreach ($_SESSION['funcionarios'] as $index => $func) {
                if ($func['id'] == $id) {
                    $funcionarioIndex = $index;
                    break;
                }
            }
            
            if ($funcionarioIndex === null) {
                enviarRespostaJson(['erro' => 'Funcionário não encontrado'], 404);
            }
            
            // Remover funcionário
            unset($_SESSION['funcionarios'][$funcionarioIndex]);
            $_SESSION['funcionarios'] = array_values($_SESSION['funcionarios']);
            
            // Remover registros associados
            $_SESSION['registros'] = array_filter($_SESSION['registros'], function($registro) use ($id) {
                return $registro['funcionario_id'] != $id;
            });
            $_SESSION['registros'] = array_values($_SESSION['registros']);
            
            enviarRespostaJson(['mensagem' => 'Funcionário excluído com sucesso']);
            break;
            
        default:
            enviarRespostaJson(['erro' => 'Método não permitido'], 405);
    }
}

// Roteamento para registros
elseif (isset($pathParts[1]) && $pathParts[1] === 'registros') {
    
    switch ($method) {
        case 'GET':
            if (isset($pathParts[2]) && is_numeric($pathParts[2])) {
                // GET /api/registros/{id}
                $id = intval($pathParts[2]);
                $registro = buscarRegistroPorId($id);
                
                if ($registro) {
                    enviarRespostaJson($registro);
                } else {
                    enviarRespostaJson(['erro' => 'Registro não encontrado'], 404);
                }
            } else {
                // GET /api/registros - com filtro opcional por funcionário
                $registros = $_SESSION['registros'];
                
                if (isset($_GET['funcionario_id']) && is_numeric($_GET['funcionario_id'])) {
                    $funcionarioId = intval($_GET['funcionario_id']);
                    $registros = array_filter($registros, function($registro) use ($funcionarioId) {
                        return $registro['funcionario_id'] == $funcionarioId;
                    });
                    $registros = array_values($registros);
                }
                
                enviarRespostaJson($registros);
            }
            break;
            
        case 'POST':
            // POST /api/registros
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                enviarRespostaJson(['erro' => 'Dados JSON inválidos'], 400);
            }
            
            $erros = validarDadosRegistro($input);
            if (!empty($erros)) {
                enviarRespostaJson(['erros' => $erros], 400);
            }
            
            $novoRegistro = [
                'id' => gerarProximoId($_SESSION['registros']),
                'funcionario_id' => intval($input['funcionario_id']),
                'data' => $input['data'],
                'hora' => $input['hora'],
                'tipo' => $input['tipo'],
                'localizacao' => $input['localizacao'],
                'observacao' => isset($input['observacao']) ? trim($input['observacao']) : ''
            ];
            
            $_SESSION['registros'][] = $novoRegistro;
            enviarRespostaJson($novoRegistro, 201);
            break;
            
        case 'PUT':
            // PUT /api/registros/{id}
            if (!isset($pathParts[2]) || !is_numeric($pathParts[2])) {
                enviarRespostaJson(['erro' => 'ID do registro é obrigatório'], 400);
            }
            
            $id = intval($pathParts[2]);
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                enviarRespostaJson(['erro' => 'Dados JSON inválidos'], 400);
            }
            
            $registroIndex = null;
            foreach ($_SESSION['registros'] as $index => $reg) {
                if ($reg['id'] == $id) {
                    $registroIndex = $index;
                    break;
                }
            }
            
            if ($registroIndex === null) {
                enviarRespostaJson(['erro' => 'Registro não encontrado'], 404);
            }
            
            $erros = validarDadosRegistro($input);
            if (!empty($erros)) {
                enviarRespostaJson(['erros' => $erros], 400);
            }
            
            $_SESSION['registros'][$registroIndex] = [
                'id' => $id,
                'funcionario_id' => intval($input['funcionario_id']),
                'data' => $input['data'],
                'hora' => $input['hora'],
                'tipo' => $input['tipo'],
                'localizacao' => $input['localizacao'],
                'observacao' => isset($input['observacao']) ? trim($input['observacao']) : ''
            ];
            
            enviarRespostaJson($_SESSION['registros'][$registroIndex]);
            break;
            
        case 'DELETE':
            // DELETE /api/registros/{id}
            if (!isset($pathParts[2]) || !is_numeric($pathParts[2])) {
                enviarRespostaJson(['erro' => 'ID do registro é obrigatório'], 400);
            }
            
            $id = intval($pathParts[2]);
            $registroIndex = null;
            
            foreach ($_SESSION['registros'] as $index => $reg) {
                if ($reg['id'] == $id) {
                    $registroIndex = $index;
                    break;
                }
            }
            
            if ($registroIndex === null) {
                enviarRespostaJson(['erro' => 'Registro não encontrado'], 404);
            }
            
            unset($_SESSION['registros'][$registroIndex]);
            $_SESSION['registros'] = array_values($_SESSION['registros']);
            
            enviarRespostaJson(['mensagem' => 'Registro excluído com sucesso']);
            break;
            
        default:
            enviarRespostaJson(['erro' => 'Método não permitido'], 405);
    }
}

// Rota não encontrada
else {
    enviarRespostaJson(['erro' => 'Endpoint não encontrado'], 404);
}
?>