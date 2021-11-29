<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'email_update_error' => 'Email já usado! Tente novamente.',

    // SIDE TOP LEFT
    'registration' => 'Matricula:',

    // SIDE TOP RIGHT
    'srt_notifications' => 'Notificações',
    'srt_see_all' => 'Ver todas notificações',
    'srt_exit' => 'SAIR',
    'srt_new_notify' => 'Novas notificações',
    'srt_empty_notify' => 'Não há novas notificações',
    'srt_mark_all' => 'Marcar todas como lidas',


        // NOTIFICATIONS

        // TRIP
        'n_trip_001_title' => 'Pedido aprovado',
        'n_trip_001' => 'Sua viagem foi aprovada, verifique sua lista de viagens.',
        'n_trip_002_title' => 'Pedido reprovado',
        'n_trip_002' => 'Infelizmente seu pedido de viagem não foi aprovado, verifique sua lista de viagens.',
        'n_trip_003_title' => 'Cotação atualizada',
        'n_trip_003' => 'A agência :Agency atualizou sua cotação para viagem :id verifique os detalhes.',
        'n_trip_004_title' => 'Bilhete de viagem',
        'n_trip_004' => 'Seu bilhete já está disponível para ser baixado, verifique os detalhes.',
        'n_trip_005_title' => 'Realize a cotação',
        'n_trip_005' => 'Nova viagem para realizar cotação, acesse os detalhes e faça a cotação.',
        'n_trip_006_title' => 'Pedido para aprovação',
        'n_trip_006' => 'Novo pedido de aprovação de viagem, acesse os detalhes e realize análise.',
        'n_trip_007_title' => 'Agência respondeu',
        'n_trip_007' => 'Agência :Agency enviou uma nova mensagem para viagem :id, veja nos detalhes da viagem.',

        // TASK
        'n_proj_001_title' => 'Nova tarefa',
        'n_proj_001' => 'Verifique em sua lista de tarefas, e não esqueça de aceitar ou recusar.',
        'n_proj_002_title' => 'Sua tarefa foi aceita',
        'n_proj_002' => 'O colaborador aceitou sua tarefa, verifique sua lista de tarefas.',
        'n_proj_003_title' => 'Tarefa foi negada',
        'n_proj_003' => 'O colaborador negou sua tarefa, acesse os detalhes e realize análise.',
        'n_proj_004_title' => 'Conclusão de tarefa',
        'n_proj_004' => 'O colaborador deseja concluir a tarefa, acesse sua lista de tarefas.',
        'n_proj_005_title' => 'Tarefa atualizado',
        'n_proj_005' => 'Histórico do tarefa foi atualizado, acesse e verifique as mudanças.',
        'n_proj_006_title' => 'Tarefa finalizada',
        'n_proj_006' => 'Sua tarefa foi finalizada, acesse os detalhes para ver mais informações.',
        'n_proj_007_title' => 'Finalização recusado',
        'n_proj_007' => 'A finalização da sua tarefa foi recusada, veja mais informações nos detalhes.',

        // CRON TASK
        'n_cron_001_title' => 'Tarefa atrasada',
        'n_cron_001' => 'A tarefa :id do colaborador :Name está atrasado, verifique os detalhes da mesma.',

        // LENDING
        'n_lending_001_title' => 'Pedido de empréstimo',
        'n_lending_001' => 'Novo pedido de empréstimo de R$ :amount do colaborador :Name, verifique a lista de aprovação.',
        'n_lending_002_title' => 'Empréstimo aprovado',
        'n_lending_002' => 'Seu empréstimo #:id no valor de R$ :amount foi aprovado! Verifique a sua lista.',
        'n_lending_003_title' => 'Empréstimo reprovado',
        'n_lending_003' => 'Seu empréstimo #:id no valor de R$ :amount foi reprovado! Verifique a sua lista.',
        'n_lending_004_title' => 'Transferência de empréstimo',
        'n_lending_004' => 'O empréstimo #:id no valor de R$ :amount foi aprovado! Verifique a lista de transferência.',
        'n_lending_005_title' => 'Empréstimo transferido',
        'n_lending_005' => 'Seu empréstimo #:id no valor de R$ :amount foi realizado a transferência! Verifique a sua lista.',

        // REFUND
        'n_refund_001_title' => 'Pedido de reembolso',
        'n_refund_001' => 'Novo pedido de reembolso do colaborador :Name, verifique a lista de aprovação.',
        'n_refund_002_title' => 'Reembolso aprovado',
        'n_refund_002' => 'Seu reembolso #:id foi aprovado! Verifique a sua lista.',
        'n_refund_003_title' => 'Reembolso reprovado',
        'n_refund_003' => 'Seu reembolso #:id foi reprovado! Verifique a sua lista.',
        'n_refund_004_title' => 'Transferência de reembolso',
        'n_refund_004' => 'O reembolso #:id foi aprovado! Verifique a sua lista.',
        'n_refund_005_title' => 'Correção de reembolso',
        'n_refund_005' => 'Foi alterado o valor de alguns itens em seu reembolso. Verifique a sua lista.',

        // PAYMENT
        'n_payment_001_title' => 'Pedido de pagamento',
        'n_payment_001' => 'Novo pedido de pagamento de R$ :amount do colaborador :Name, verifique a lista de aprovação.',
        'n_payment_002_title' => 'Pagamento aprovado',
        'n_payment_002' => 'Seu pagamento #:id no valor de R$ :amount foi aprovado! Verifique a sua lista.',
        'n_payment_003_title' => 'Pagamento reprovado',
        'n_payment_003' => 'Seu pagamento #:id no valor de R$ :amount foi reprovado! Verifique a sua lista.',
        'n_payment_004_title' => 'Transferência de pagamento',
        'n_payment_004' => 'O pagamento #:id no valor de R$ :amount foi aprovado! Verifique a lista de transferência.',
        'n_payment_005_title' => 'Pagamento transferido',
        'n_payment_005' => 'Seu pagamento #:id no valor de R$ :amount foi realizado a transferência! Verifique a sua lista.',

        // Accountability
        'n_accountability_001_title' => 'Pedido de Prestação de Contas',
        'n_accountability_001' => 'Novo pedido de prestação de contas de :Name, verifique a sua lista.',
        'n_accountability_002_title' => 'Prestação de Contas aprovada',
        'n_accountability_002' => 'Sua Prestação de Contas #:id foi aprovada! Verifique a sua lista.',
        'n_accountability_003_title' => 'Prestação de Contas reprovada',
        'n_accountability_003' => 'Sua Prestação de Contas #:id foi reprovada! Verifique a sua lista.',
        'n_accountability_004_title' => 'Reembolso de Prestação de Contas',
        'n_accountability_004' => 'Sua Prestação de Contas #:id foi reembolsada! Verifique a sua lista.',
        'n_accountability_005_title' => 'Correção de Prestação de Contas',
        'n_accountability_005' => 'Foi alterado o valor de alguns itens em sua Prestação de Contas. Verifique a sua lista.',
        


    // MISC
    'btn_next' => 'Próximo',
    'btn_previous' => 'Anterior',
    'btn_confirm' => 'Confirmar',
    'btn_cancel' => 'Cancelar',
    'op_edit' => 'Editar',
    'op_delete' => 'Deletar',
    'op_see_routes' => 'Ver rotas',
    'op_ticket' => 'Bilhete',
    'op_hotel' => 'Hotel',
    'op_history' => 'Ver histórico',
    'op_inactive' => 'Desativar',
    'op_active' => 'Ativar',

    // FOOTER
    'footer_description' => 'Sistema de acesso interno.',

    // MENU SIDE LEFT
    'menu_news' => 'Notícias & Avisos',
    'menu_news_subtitle' => 'Tudo que se passa pela Gree',
    'menu_news_post' => 'Nova publicação',
    'menu_news_posts' => 'Todas publicações',
    'menu_news_publish' => 'Publicações',
    'menu_news_author' => 'Autores',
    'menu_news_list_transmission' => 'Lista de transmissão',
    'menu_news_categories' => 'Categorias',
    'menu_admin' => 'Administração',
    'menu_my_profile' => 'Meu perfil',
    'menu_my_task' => 'Tarefas',
    'menu_users' => 'Usuários',
    'menu_users_new' => 'Novo usuário',
    'menu_users_list' => 'Listar usuários',
    'menu_users_log' => 'Log de usuários',
    'menu_trip' => 'Viagem',
    'menu_trip_view' => 'Aprovar solicitações',
    'menu_trip_view_subtitle' => 'Aprove ou reprove solicitações',
    'menu_trip_view_approv' => 'Todas solicitações',
    'menu_trip_view_approv_subtitle' => 'Faça cotação das viagens aprovadas.',
    'menu_trip_agency' => 'Ver agências',
    'menu_trip_agency_subtitle' => 'Crie ou edite!',
    'menu_trip_credit' => 'Viagens creditadas',
    'menu_trip_credit_subtitle' => 'Compre outras passagens com essas viagens.',
    'menu_trip_my' => 'Meus planejamentos',
    'menu_trip_my_subtitle' => 'Envie para aprovação suas rotas',
    'menu_trip_all' => 'Todos planejamentos',
    'menu_trip_all_subtitle' => 'Verifica todos os planejamentos',
    'menu_trip_dashboard' => 'Relatório',
    'menu_trip_dashboard_subtitle' => 'Informações de voos já feito orçamento.',
    'menu_trip_new' => 'Nova programação',
    'menu_trip_new_subtitle' => 'Crie toda a sua programação do mês.',
    'menu_trip_export' => 'Exportar dados',
    'menu_trip_export_subtile' => 'Baixe dados em excel das viagens feitas.',
    'menu_lending' => 'Empréstimo',
    'menu_lending_report' => 'Relatório',
    'menu_lending_new' => 'Novo empréstimo',
    'menu_lending_my' => 'Meus empréstimos',
    'menu_lending_approv' => 'Aprovar empréstimo',
    'menu_lending_all' => 'Todos empréstimos',
    'menu_lending_transfer' => 'Transferir empréstimos',
    'menu_lending_export' => 'Exportar dados',
    'menu_project' => 'Tarefas',
    'menu_project_new' => 'Nova tarefa',
    'menu_project_my' => 'Minhas tarefas',
    'menu_project_approv' => 'Aprovar tarefas',
    'menu_project_view' => 'Ver tarefas',
    'menu_project_export' => 'Exportar tarefas',
    'menu_commercial' => 'Comercial',
    'menu_homeoffice' => 'Home office',
    'menu_homeoffice_cron' => 'Cronômetro de trabalho',
    'menu_homeoffice_report' => 'Meus relatórios',
    'menu_homeoffice_data' => 'Banco de horas',
    'menu_homeoffice_online' => 'Colaboradores ativos',
    
    // DATA TABLE LANG
    'dtbl_search' => 'Pesquisar:',
    'dtbl_zero_records' => 'Sem resultados com base na pesquisa',
    'dtbl_info' => 'Visualizando _START_ de _END_ do total: _TOTAL_',
    'dtbl_info_empty' => 'Visualizando 0 de 0 ',
    'dtbl_info_filtred' => '(Filtro do total: _MAX_)',

    // MODAL INPUT EMAIL
    'mie_finalized' => 'Concluir',
    'mie_input_email' => 'Seu email',
    'mie_description' => 'Primeiro acesso!',
    'mie_ps' => 'Digite seu email da matricula, não precisa digitar @ e afins.',
    'mie_title' => 'ATUALIZAR CONTA',


    // VERSION LANG MODAL
    'vlm_version' => 'Versão',
    'vlm_close' => 'FECHAR',
    'vlm_updated' => 'ATUALIZAÇÕES',
    'vlm_version_title_1' => 'Notícias',
    'vlm_version_body_1' => 'Confira nosso portal de notícias sobre o que acontece dentro e fora da gree, aproveite e interaja conosco sobre o que acha em determinadas notícias publicadas por nós.',
    'vlm_version_title_2' => 'Viagem',
    'vlm_version_body_2' => 'Precisa programar sua viagem pra fora ou até mesmo para outro estado, agora é possível realizar o pedido aqui na plataforma e aguardar aprovação dinâmicamente.',

    // DROPDOWN SECTOR AND SUB SECTOR
    'sct_name_1' => 'Comercial (CRAC)',
    'sct_name_2' => 'Industrial',
    'sct_name_3' => 'Financeiro',
    'sct_name_4' => 'Expedição & Recebimento',
    'sct_name_5' => 'Importação & Exportação',
    'sct_name_6' => 'Administração',
    'sct_name_7' => 'Recursos humanos',
    'sct_name_8' => 'Compras',
    'sct_name_9' => 'TI',
    'sct_name_10' => 'Manutenção',
    'sct_name_11' => 'Recepção',
    'sct_name_12' => 'Comercial (CAC)',
    'sct_name_13' => 'Comercial Internacional',
    'sct_name_14' => 'Produção',
    'sct_name_15' => 'Engenharia',
    'sct_name_16' => 'Pós venda',
    'sct_name_17' => 'Assistência técnica',
    'sct_name_18' => 'SAC',
    'sct_name_19' => 'P&D',
    'sct_name_20' => 'Certificação',
    'sct_name_21' => 'Treinamento',
    'sct_name_22' => 'Jurídico',
    'sct_name_23' => 'Qualidade',
    'sct_name_24' => 'Logistica',
    'sct_name_25' => 'Trade',
    'sct_name_99' => 'Geral',
    'sct_name_100' => 'Marketing Interno',

    'sct_2_name_1' => 'Produtos importados',
    'sct_2_name_2' => 'Marketing e treinamento',
    'sct_2_name_3' => 'Produtos industriais',
    'sct_2_name_4' => 'Assistência técnica',
    'sct_2_name_5' => 'Engenharia',
    'sct_2_name_6' => 'Produção',
    'sct_2_name_7' => 'CQ',

    'sct_3_name_1' => 'Certificação de produto',
    'sct_3_name_2' => 'Assist. Tec. de produtos residência e comercial',
    'sct_3_name_3' => 'SAC',

    // NOT PERMISSIONS
    'not_permissions' => 'Você não tem permissão para acessar essa página.',

];