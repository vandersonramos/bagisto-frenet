<?php

return [
    [
        'key' => 'sales.carriers.vandersonramos_frenet',
        'name' => 'Frenet',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'active',
                'title' => 'admin::app.admin.system.status',
                'type'  => 'boolean',
                'options' => [
                    [
                        'title' => 'Sim',
                        'value' => true
                    ], [
                        'title' => 'Não',
                        'value' => false
                    ]
                ],
                'validation' => 'required'
            ],
            [
                'name' => 'title',
                'title' => 'Título',
                'type' => 'text',
                'validation' => 'required',
            ],
            [
                'name' => 'login',
                'title' => 'Usuário',
                'type' => 'text',
            ],
            [
                'name' => 'password',
                'title' => 'Senha',
                'type' => 'text',
            ],
            [
                'name' => 'token',
                'title' => 'Token de Acesso',
                'type' => 'text',
            ],
            [
                'name' => 'dimension_type',
                'title' => 'Tipo de dimensão',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Centímetros',
                        'value' => 'cm'
                    ], [
                        'title' => 'Metros',
                        'value' => 'm'
                    ]
                ],
            ],
            [
                'name' => 'weight_type',
                'title' => 'Formato de peso',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Kilos',
                        'value' => 'kg'
                    ], [
                        'title' => 'Gramas',
                        'value' => 'gr'
                    ]
                ],
            ],
            [
                'name' => 'show_delivery_time',
                'title' => 'Exibir Prazo de Entrega',
                'type'  => 'boolean',
                'options' => [
                    [
                        'title' => 'Sim',
                        'value' => true
                    ], [
                        'title' => 'Não',
                        'value' => false
                    ]
                ],
            ],
            [
                'name' => 'add_days',
                'title' => 'Adicionar ao Prazo de Entrega (Dias)',
                'type' => 'text',
            ],
        ]
    ]
];