<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Lista de Permissões
    |
    | Exemplo de Como acessar essa configuração
    | $permissions = config('gree.permissions');
    */
    'permissions' => [
         1 => ['id' => 1  ,'name' => 'Viagem'               ,'description' => 'Permissão para uso do módulo de viagem.'],
         2 => ['id' => 2  ,'name' => 'Usuários'             ,'description' => 'Usado para cadastrar novos usuários no sistema, editar e ver logs de acessos.'],
         3 => ['id' => 3  ,'name' => 'Tarefas'              ,'description' => 'Módulo de tarefas, envie tarefas para os colaboradores e acompanhe as atualizações'],
         4 => ['id' => 4  ,'name' => 'TI'                   ,'description' => 'Atualizar sistema, realizar manutenção da TI da fabrica...'],
         5 => ['id' => 5  ,'name' => 'Notícias'             ,'description' => 'Criação das publicações para midia sociais externas e midia interna.'],
         6 => ['id' => 6  ,'name' => 'Sac'                  ,'description' => 'Permissão destinada ao atendimento ao cliente.'],
         7 => ['id' => 7  ,'name' => 'Pedido de Compras'    ,'description' => 'Permissão destinada para venda de peças.'],
         8 => ['id' => 8  ,'name' => 'Configurações'        ,'description' => 'Atualiza informações importantes no sistema e no site.'],
         9 => ['id' => 9  ,'name' => 'Empréstimo'           ,'description' => 'Usado para pedido de emprestimos.'],
        10 => ['id' => 10 ,'name' => 'Home office'          ,'description' => 'O colaborador pode trabalhar de casa se quiser.'],
        11 => ['id' => 11 ,'name' => 'Pagamentos'           ,'description' => 'Usado no módulo de solicitação de pagamentos'],
        12 => ['id' => 12 ,'name' => 'Reembolso'            ,'description' => 'Módulo de solicitação de reembolso.'],
        13 => ['id' => 13 ,'name' => 'Pesquisa'             ,'description' => 'Você pode enviar um formulário de pergunta para os usuários responderem ao acessar o sistema.'],
        14 => ['id' => 14 ,'name' => 'Industrial'           ,'description' => 'Módulo usado para controle de produtos, peças e estoque da empresa.'],
        15 => ['id' => 15 ,'name' => 'Engenharia'           ,'description' => 'Usado para criação de produtos, ativação de produtos, vinculação e criação de peças.'],
        16 => ['id' => 16 ,'name' => 'Assistência técnica'  ,'description' => 'Aprovação de atendimento em garantia, aprovação de envio de peças e acompanhamento de envio de peças.'],
        17 => ['id' => 17 ,'name' => 'Expedição'            ,'description' => 'Usado para controle de envio e recebimento de produtos.'],
        18 => ['id' => 18 ,'name' => 'Financeiro'           ,'description' => 'As pessoas que tiverem essa permissão e aprovação tbm, poderão ver todas as informações dos módulos financiais.'],
        19 => ['id' => 19 ,'name' => 'Qrcode'               ,'description' => 'Permissão destinada para qualquer campanha envolvendo o uso do QRCODE nos equipamentos.'],
        20 => ['id' => 20 ,'name' => 'Comercial'            ,'description' => 'Usado para gerenciar promotores, pedidos, provisionamento de produção, entre outros...'],
        21 => ['id' => 21 ,'name' => 'Promotor'             ,'description' => 'Módulo usado para gerenciar promotores em campo do comercial'],
        22 => ['id' => 22 ,'name' => 'Prestação de Contas'  ,'description' => 'Módulo usado para gerenciar as Prestações de Contas dos Emprestimos Realizados'],
        23 => ['id' => 23 ,'name' => 'Juridico'             ,'description' => 'Usado operações administrativas do juridico'],
        24 => ['id' => 24 ,'name' => 'RH'                   ,'description' => 'Usado para uso básico de funcionários'],
		25 => ['id' => 25 ,'name' => 'Prova de recrutamento'                   ,'description' => 'Módulo de aplicação de provas no processo de recrutamento.'],
		26 => ['id' => 26 ,'name' => 'Logística'                   ,'description' => 'Módulo Logística.'],
		27 => ['id' => 27 ,'name' => 'Administrativo'                   ,'description' => 'Módulo Administrativo.'],

        ],

    /*
    |--------------------------------------------------------------------------
    | Permissions Module
    |--------------------------------------------------------------------------
    |
    | Lista de Permissões de Modulos
    |
    | Exemplo de Como acessar essa configuração
    | $permissions_module = config('gree.permissions_module');
    */
    'permissions_module' => [
        1=>'Empréstimo',
        2=>'Reembolso',
        3=>'Pagamento',
        4=>'Prestação de Contas',
    ],

    'states' => [
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas', 'BA' => 'Bahia', 'CE' => 'Ceará',
        'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão',
        'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais', 'PA' => 'Pará',
        'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima',
        'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    ],

    'region' => [
        1 => 'Norte',
        2 => 'Nordeste',
        3 => 'Leste',
        4 => 'Sudeste',
        5 => 'Sul',
        6 => 'Sudoeste',
        7 => 'Oeste',
        8 => 'Noroeste'
    ],
	
	'sector' => [
        1 => 'Comercial',
        2 => 'Industrial',
        3 => 'Financeiro',
        4 => 'Expedição & Recebimento',
        5 => 'Importação & Exportação',
        6 => 'Administração',
        7 => 'Recursos humanos',
        8 => 'Compras',
        9 => 'TI',
        10 => 'Manutenção',
        99 => 'Geral',
        100 => 'Marketing Interno',
        101 => 'Recepção',
        102 => 'Comercial (CAC)',
        103 => 'Comercial Internacional',
        104 => 'Produção',
        105 => 'Engenharia',
        106 => 'Pós venda',
        107 => 'Assistência técnica',
        108 => 'SAC',
        109 => 'P&D',
        110 => 'Certificação',
        111 => 'Treinamento',
        112 => 'Jurídico',
        113 => 'Qualidade',
        114 => 'Logistica',
        115 => 'Trade'
    ],
	
	'type_sac_protocol' => [   
        1 => 'Reclamação',
        2 => 'Atend. em garantia',
        3 => 'Dúvida técnica',
        4 => 'Revenda',
        5 => 'Credenciamento',
        7 => 'Atendimento fora de garantia',
        8 => 'Atendimento negado (erro de inst.)',
        9 => 'Autorização de instalação',
        10 => 'Atendimento tercerizado',
        11 => 'Atendimento em cortesia',
        6 => 'Outros'
    ],
	
	/*
    |--------------------------------------------------------------------------
    | Permissions Commercial
    |--------------------------------------------------------------------------
    |
    | Lista de Permissões do comercial
    |
    | Exemplo de Como acessar essa configuração
    | $permissions = config('gree.permmisions_commercial_scheme');
    */
    'permmisions_commercial_scheme' => [
		'operational' => [
			'page_name' => 'Operacional',
            'apuracao_faturamento' => 'Apuração de faturamento ',
            'apuracao_vendas' => 'Apuração de vendas',
			'pedidos_faturados' => 'Pedidos faturados'
        ],
        'pedidos_de_vendas' => [
            'page_name' => 'Pedido de vendas',
            'reajuste_comercial' => 'Reajuste Comercial',
            'programacoes' => 'Programações',
            'pedidos_programados' => 'Pedidos programados',
            'pedidos_nao_programados' => 'Pedidos não programados',
        ],
        'representantes' => [
            'page_name' => 'Representantes',
            'representantes' => 'Representantes',
        ],
        'clientes' => [
            'page_name' => 'Clientes',
            'grupos_clientes' => 'Grupo de clientes',
            'clientes' => 'Clientes',
            'condicoes_tabela_preco' => 'Tabela de preço',
            'condicoes_regra_preco' => 'Regra de preço',
        ],
        'produtos' => [
            'page_name' => 'Produtos',
            'grupos_produtos' => 'Grupo de produtos',
            'produtos' => 'Produtos',
        ],
        'configuracoes' => [
            'page_name' => 'Configurações',
            'configuracoes' => 'Configurações',
        ],
    ],
	
	'arr_states' => [
        "AC" => ["name" => "Acre", "region" => "Norte"],
        "AL" => ["name" => "Alagoas", "region" => "Nordeste"],
        "AM" => ["name" => "Amazonas", "region" => "Norte"],
        "AP" => ["name" => "Amapá", "region" => "Norte"],
        "BA" => ["name" => "Bahia", "region" => "Nordeste"],
        "CE" => ["name" => "Ceará", "region" => "Nordeste"],
        "DF" => ["name" => "Distrito Federal", "region" => "Centro-Oeste"],
        "ES" => ["name" => "Espírito Santo", "region" => "Sudeste"],
        "GO" => ["name" => "Goiás", "region" => "Centro-Oeste"],
        "MA" => ["name" => "Maranhão", "region" => "Nordeste"],
        "MT" => ["name" => "Mato Grosso", "region" => "Centro-Oeste"],
        "MS" => ["name" => "Mato Grosso do Sul", "region" => "Centro-Oeste"],
        "MG" => ["name" => "Minas Gerais", "region" => "Sudeste"],
        "PA" => ["name" => "Pará", "region" => "Norte"],
        "PB" => ["name" => "Paraíba", "region" => "Nordeste"],
        "PR" => ["name" => "Paraná", "region" => "Sul"],
        "PE" => ["name" => "Pernambuco", "region" => "Nordeste"],
        "PI" => ["name" => "Piauí", "region" => "Nordeste"],
        "RJ" => ["name" => "Rio de Janeiro", "region" => "Sudeste"],
        "RN" => ["name" => "Rio Grande do Norte", "region" => "Nordeste"],
        "RO" => ["name" => "Rondônia", "region" => "Norte"],
        "RS" => ["name" => "Rio Grande do Sul", "region" => "Sul"],
        "RR" => ["name" => "Roraima", "region" => "Norte"],
        "SC" => ["name" => "Santa Catarina", "region" => "Sul"],
        "SE" => ["name" => "Sergipe", "region" => "Nordeste"],
        "SP" => ["name" => "São Paulo", "region" => "Sudeste"],
        "TO" => ["name" => "Tocantins", "region" => "Norte"],
    ],
	
	"arr_region" => [
        "Sul" => ["PR","RS","SC"],
        "Sudeste" => ["SP","RJ","ES","MG"],
        "Centro-Oeste" => ["MT","MS","GO"],
        "Norte" => ["AM","RR","AP","PA","TO","RO","AC"],
        "Nordeste" => ["MA","PI","CE","RN","PE","PB","SE","AL","BA"]
    ],
	
	'type_vehicle' => [   
        1 => 'VUC',
        2 => 'Caminhão Toco',
        3 => 'Cavalo mecânico 2 eixos',
        4 => 'Cavalo mecânico com três eixos',
        5 => 'Cavalo Mecânico Traçado',
        6 => 'Bitrem',
        7 => 'Rodotrem',
        8 => 'Truck',
        9 => 'Bitruck',
		10 => 'Outros',
    ],

    'type_cart' => [   
        1 => 'Carreta Baú',
        2 => 'Carreta Sider',
        3 => 'Porta Container',
        4 => 'Carreta Prancha',
        5 => 'Carreta Basculhante',
        6 => 'Carreta Plataforma'
    ],
	
	'months' => [
        1 => "Janeiro", 
        2 => "Fevereiro",
        3 => "Março", 
        4 => "Abril", 
        5 => "Maio", 
        6 => "Junho", 
        7 => "Julho", 
        8 => "Agosto", 
        9 => "Setembro", 
        10 => "Outubro", 
        11 => "Novembro", 
        12 => "Dezembro"
    ],
	
	'analyze_office_mark' => [
        'financy' => [
            2 => 'Recebedor',
            3 => 'Verificador Fiscal',
            4 => 'Verificador Contábil',
            5 => 'Gerente financeiro',
            6 => 'Diretor'
        ]
    ]
    
];
