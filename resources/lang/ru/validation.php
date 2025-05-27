<?php

return [
    'current_password' => 'Текущий пароль указан неверно',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'email' => 'Поле :attribute должно быть действительным email адресом.',
    'unique' => 'Такой :attribute уже существует.',
    'confirmed' => 'Пароли не совпадают.',
    'min' => [
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    // Другие необходимые правила валидации
    'attributes' => [
        'name' => 'Имя',
        'email' => 'Email',
        'current_password' => 'Текущий пароль',
        'password' => 'Пароль',
    ],
];