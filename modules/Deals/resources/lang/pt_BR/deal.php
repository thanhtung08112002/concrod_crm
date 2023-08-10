<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.2.1
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2023 KONKORD DIGITAL
 */

return [
    'deal' => 'Negócio',
    'deals' => 'Negócios',
    'create' => 'Criar Negócio',
    'add' => 'Adicionar Negócio',
    'sort_by' => 'Ordenar negócios por',
    'name' => 'Nome do Negócio',
    'choose_or_create' => 'Escolha ou crie um negócio',
    'associate_with' => 'Associar negócio com :name',
    'add_products' => 'Adicionar produtos',
    'dont_add_products' => 'Não adicionar produtos',
    'reopen' => 'Reabrir',
    'won_date' => 'Data do Ganho',
    'lost_date' => 'Data da Perda',
    'status_related_filter_notice' => 'Esta regra é aplicável principalmente ao filtrar negócios com o status ":status".',
    'status' => [
        'status' => 'Status',
        'won' => 'Ganho',
        'lost' => 'Perdido',
        'open' => 'Aberto',
    ],
    'been_in_stage_time' => 'Esteve aqui por :time',
    'hasnt_been_in_stage' => 'Este negócio ainda não está nesta fase',
    'total_created' => 'Total Criado',
    'total_assigned' => 'Total Associado',
    'import' => 'Importar Negócios',
    'export' => 'Exportar Negócios',
    'import_in' => 'Importar Negócios em :pipeline',
    'total' => 'Total de Negócios',
    'closed_deals' => 'Negócios Fechados',
    'won_deals' => 'Negócios Ganhos',
    'open_deals' => 'Negócios Abertos',
    'lost_deals' => 'Negócios Perdidos',
    'forecast_amount' => 'Valor Previsto',
    'closed_amount' => 'Valor Fechado',
    'dissociate' => 'Acordo Dissociado',
    'no_companies_associated' => 'O negócio não tem empresas associadas.',
    'no_contacts_associated' => 'O negócio não tem contatos associados.',
    'associate_field_info' => 'Use este campo para associar um negócio existente em vez de criar um novo.',
    'create_with' => 'Criar Negócio com :name',
    'already_associated' => 'Este negócio já está associado a :with.',
    'lost_reasons' => [
        'lost_reason' => 'Motivo da Perda',
        'lost_reasons' => 'Motivos da Perda',
        'name' => 'Nome',
        'choose_lost_reason' => 'Escolha um motivo da perda',
        'choose_lost_reason_or_enter' => 'Escolha um motivo da perda ou insira manualmente',
    ],
    'settings' => [
        'lost_reason_is_required' => 'O motivo da perda é obrigatório',
        'lost_reason_is_required_info' => 'Quando ativado, os agentes de vendas deverão escolher ou inserir o motivo da perda ao marcar o negócio como perdido.',
        'allow_lost_reason_enter' => 'Permitir que os agentes de vendas insiram o motivo de perda personalizado',
        'allow_lost_reason_enter_info' => 'Quando desativado, os agentes de vendas poderão escolher apenas na lista predefinida de motivos de perda ao marcar o negócio como perdido.',
    ],
    'cards' => [
        'by_stage' => 'Negócios por etapa',
        'lost_in_stage' => 'Etapa de negócios perdidos',
        'lost_in_stage_info' => 'Veja em que estágio os negócios estão mais perdidos. As etapas mostradas nos relatórios são as etapas a que o negócio pertencia no momento em que foi marcado como perdido.',
        'won_in_stage' => 'Etapa de negócios ganhos',
        'won_in_stage_info' => 'Veja em que estágio os negócios são mais fechados. As etapas mostradas nos relatórios são as etapas às quais o negócio pertencia no momento em que foi marcado como ganho.',
        'closing' => 'Fechando negócios',
        'closing_info' => 'Veja os negócios que estão previstos para serem fechados com base no período selecionado e na data de fechamento esperada, os negócios marcados como "ganhos" ou "perdidos" são excluídos da lista.',
        'recently_created' => 'Ofertas criadas recentemente',
        'recently_modified' => 'Ofertas modificadas recentemente',
        'won_by_revenue_by_month' => 'Receita de negócios ganhos por mês',
        'won_by_date' => 'Negócios ganhos por dia',
        'assigned_by_sale_agent' => 'Negócios atribuídos por agente de vendas',
        'assigned_by_sale_agent_info' => 'Visualize o número total de negócios atribuídos a cada representante de vendas. Veja quanta receita esses negócios provavelmente trarão para sua empresa. E quanta receita você já tem de negócios fechados.',
        'created_by_sale_agent' => 'Negócios criados por agente de vendas',
        'created_by_sale_agent_info' => 'Veja quais representantes de vendas estão criando mais negócios. Veja quanta receita esses negócios provavelmente trarão para sua empresa. E quanta receita você já tem de negócios fechados.',
        'recently_created_info' => 'Mostrando os últimos :total negócios criados nos últimos :days dias, classificados pelos mais novos no topo.',
        'recently_modified_info' => 'Mostrando os últimos :total negócios modificados nos últimos :days dias.',
        'won_by_month' => 'Negócios ganhos por mês',
    ],
    'notifications' => [
        'assigned' => 'Você foi atribuído ao negócio :name por :user',
    ],
    'stage' => [
        'weighted_value' => ':weighted_total - :win_probability de :total',
        'changed_date' => 'Data de Mudança da Etapa',
        'add' => 'Adicionar Nova Etapa',
        'name' => 'Nome da Etapa',
        'win_probability' => 'Probabilidade de Ganhar',
        'delete_usage_warning' => 'O estágio já está associado a negócios, portanto, não pode ser excluído.',
    ],
    'deal_amount' => 'Valor do negócio',
    'deal_expected_close_date' => 'Data prevista para fechamento do negócio',
    'count' => [
        'all' => '1 negócio | :count negócios',
        'open' => ':resource contagem de negócios abertos',
        'won' => ':resource contagem de negócios ganhos',
        'lost' => ':resource contagem de negócios perdidos',
        'closed' => ':resource contagem de negócios fechados',
    ],
    'pipeline' => [
        'name' => 'Nome da Pipeline',
        'pipeline' => 'Pipeline',
        'pipelines' => 'Pipelines',
        'create' => 'Criar Pipeline',
        'edit' => 'Editar Pipeline',
        'updated' => 'Pipeline atualizado com sucesso',
        'deleted' => 'Pipeline excluído com sucesso',
        'delete_primary_warning' => 'Você não pode excluir o pipeline primário.',
        'delete_usage_warning_deals' => 'O pipeline já está associado a negócios, portanto, não pode ser excluído.',
        'visibility_group' => [
            'primary_restrictions' => 'Este é o pipeline principal, portanto, a visibilidade não pode ser alterada.',
        ],
        'reorder' => 'Reordenar pipelines',
        'remember_sorting_info' => 'A classificação será lembrada para o pipeline :name.',
        'previous_remember_sorting_note' => 'Observe que suas opções de classificação lembradas anteriormente para o pipeline :name serão substituídas.',
    ],
    'actions' => [
        'change_stage' => 'Mudar Etapa',
        'mark_as_open' => 'Marcar como aberto',
        'mark_as_won' => 'Marcar como ganho',
        'mark_as_lost' => 'Marcar como perdido',
    ],
    'filters' => [
        'my' => 'Meus Negócios',
        'my_recently_assigned' => 'Meus Negócios Recentemente Atribuídos',
        'created_this_month' => 'Negócios Criados Este Mês',
        'won' => 'Negócios Ganhos',
        'lost' => 'Negócios Perdidos',
        'open' => 'Negócios Abertos',
    ],
    'mail_placeholders' => [
        'assigneer' => 'O nome do usuário que atribuiu o negócio',
    ],
    'workflows' => [
        'triggers' => [
            'status_changed' => 'Status do Negócio Alterado',
            'stage_changed' => 'Etapa do Negócio Alterada',
            'created' => 'Negócio Criado',
        ],
        'actions' => [
            'mark_associated_activities_as_complete' => 'Marcar Atividades Associadas Como Concluídas',
            'mark_associated_deals_as_won' => 'Marcar Negócios Associados Como Ganhos',
            'mark_associated_deals_as_lost' => 'Marcar Negócios Associados Como Perdidos',
            'delete_associated_activities' => 'Excluir Atividades Associadas',
            'fields' => [
                'email_to_contact' => 'Contato primário do negócio',
                'email_to_company' => 'Empresa primária do negócio',
                'email_to_owner_email' => 'E-mail do proprietário do negócio',
                'email_to_creator_email' => 'E-mail do criador do negócio',
                'lost_reason' => 'Com a seguinte razão da perda',
            ],
        ],
    ],
    'timeline' => [
        'stage' => [
            'moved' => ':user moveu o negócio da etapa :previous para :stage',
        ],
        'marked_as_lost' => ':user marcou o negócio com perdido pelo seguinte motivo: :reason',
        'marked_as_won' => ':user marcou o negócio como ganho',
        'marked_as_open' => ':user marcou o negócio como aberto',
        'deleted' => 'O negócio foi excluído por :causer',
        'restored' => 'O negócio foi restaurado da lixeira por :causer',
        'created' => 'Negócio foi criado por :causer',
        'updated' => 'Negócio foi atualizado por :causer',
        'attached' => 'Negócio associado a :user',
        'detached' => 'Negócio dissociado de :user',
        'associate_trashed' => 'O negócio :dealName associado foi movido para a lixeira por :user',
    ],
    'highlights' => [
        'open' => 'Negócios Abertos',
    ],
    'empty_state' => [
        'title' => 'Você não criou nenhum negócio.',
        'description' => 'Comece criando um novo negócio.',
    ],
];
