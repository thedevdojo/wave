<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Pole :attribute musi być zaakceptowane.',
    'active_url'           => 'Pole :attribute nie jest prawidłowym adresem URL.',
    'after'                => 'Pole :attribute musi być datą po :date.',
    'after_or_equal'       => 'Pole :attribute musi być datą równą lub po :date.',
    'alpha'                => 'Pole :attribute może zawierać tylko litery.',
    'alpha_dash'           => 'Pole :attribute może zawierać tylko litery, cyfry, myślniki i podkreślenia.',
    'alpha_num'            => 'Pole :attribute może zawierać tylko litery i cyfry.',
    'array'                => 'Pole :attribute musi być tablicą.',
    'before'               => 'Pole :attribute musi być datą przed :date.',
    'before_or_equal'      => 'Pole :attribute musi być datą przed lub równą :date.',
    'between'              => [
        'numeric' => 'Pole :attribute musi mieścić się między :min a :max.',
        'file'    => 'Pole :attribute musi mieścić się między :min a :max kilobajtów.',
        'string'  => 'Pole :attribute musi mieścić się między :min a :max znaków.',
        'array'   => 'Pole :attribute musi mieć od :min do :max elementów.',
    ],
    'boolean'              => 'Pole :attribute musi być prawda lub fałsz.',
    'confirmed'            => 'Potwierdzenie :attribute nie pasuje.',
    'current_password'     => 'Hasło jest niepoprawne.',
    'date'                 => 'Pole :attribute nie jest prawidłową datą.',
    'date_equals'          => 'Pole :attribute musi być datą równą :date.',
    'date_format'          => 'Pole :attribute nie pasuje do formatu :format.',
    'declined'             => 'Pole :attribute musi być odrzucone.',
    'declined_if'          => 'Pole :attribute musi być odrzucone jeśli :other jest :value.',
    'different'            => 'Pole :attribute i :other muszą być różne.',
    'digits'               => 'Pole :attribute musi mieć :digits cyfr.',
    'digits_between'       => 'Pole :attribute musi mieścić się między :min a :max cyframi.',
    'dimensions'           => 'Pole :attribute ma nieprawidłowe wymiary obrazu.',
    'distinct'             => 'Pole :attribute ma zduplikowaną wartość.',
    'email'                => 'Pole :attribute musi być prawidłowym adresem e-mail.',
    'ends_with'            => 'Pole :attribute musi się kończyć jednym z podanych: :values.',
    'enum'                 => 'Atrybut :attribute jest niepoprawny.',
    'exists'               => 'Wybrane pole :attribute jest nieprawidłowe.',
    'file'                 => 'Pole :attribute musi być plikiem.',
    'filled'               => 'Pole :attribute jest wymagane.',
    'gt'                   => [
        'numeric' => 'Pole :attribute musi być większe niż :value.',
        'file'    => 'Pole :attribute musi być większe niż :value kilobajtów.',
        'string'  => 'Pole :attribute musi być dłuższe niż :value znaków.',
        'array'   => 'Pole :attribute musi mieć więcej niż :value elementów.',
    ],
    'gte'                  => [
        'numeric' => 'Pole :attribute musi być większe lub równe :value.',
        'file'    => 'Pole :attribute musi być większe lub równe :value kilobajtów.',
        'string'  => 'Pole :attribute musi być dłuższe lub równe :value znaków.',
        'array'   => 'Pole :attribute musi mieć :value lub więcej elementów.',
    ],
    'image'                => 'Pole :attribute musi być obrazem.',
    'in'                   => 'Wybrane pole :attribute jest nieprawidłowe.',
    'in_array'             => 'Pole :attribute nie istnieje w :other.',
    'integer'              => 'Pole :attribute musi być liczbą całkowitą.',
    'ip'                   => 'Pole :attribute musi być prawidłowym adresem IP.',
    'ipv4'                 => 'Pole :attribute musi być prawidłowym adresem IPv4.',
    'ipv6'                 => 'Pole :attribute musi być prawidłowym adresem IPv6.',
    'json'                 => 'Pole :attribute musi być prawidłowym ciągiem JSON.',
    'lt'                   => [
        'numeric' => 'Pole :attribute musi być mniejsze niż :value.',
        'file'    => 'Pole :attribute musi być mniejsze niż :value kilobajtów.',
        'string'  => 'Pole :attribute musi być krótsze niż :value znaków.',
        'array'   => 'Pole :attribute musi mieć mniej niż :value elementów.',
    ],
    'lte'                  => [
        'numeric' => 'Pole :attribute musi być mniejsze lub równe :value.',
        'file'    => 'Pole :attribute musi być mniejsze lub równe :value kilobajtów.',
        'string'  => 'Pole :attribute musi być krótsze lub równe :value znaków.',
        'array'   => 'Pole :attribute nie może mieć więcej niż :value elementów.',
    ],
    'mac_address'          => 'Pole :attribute musi być poprawnym adresem MAC.',
    'max'                  => [
        'numeric' => 'Pole :attribute nie może być większe niż :max.',
        'file'    => 'Pole :attribute nie może być większe niż :max kilobajtów.',
        'string'  => 'Pole :attribute nie może być dłuższe niż :max znaków.',
        'array'   => 'Pole :attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes'                => 'Pole :attribute musi być plikiem typu: :values.',
    'mimetypes'            => 'Pole :attribute musi być plikiem typu: :values.',
    'min'                  => [
        'numeric' => 'Pole :attribute musi mieć co najmniej :min.',
        'file'    => 'Pole :attribute musi mieć co najmniej :min kilobajtów.',
        'string'  => 'Pole :attribute musi mieć co najmniej :min znaków.',
        'array'   => 'Pole :attribute musi mieć co najmniej :min elementów.',
    ],
    'multiple_of'          => 'Pole :attribute musi być wielokrotnością pola :value.',
    'not_in'               => 'Wybrane pole :attribute jest nieprawidłowe.',
    'not_regex'            => 'Format pola :attribute jest nieprawidłowy.',
    'numeric'              => 'Pole :attribute musi być liczbą.',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present'              => 'Pole :attribute musi być obecne.',
    'prohibited'           => 'Pole :attribute jest zabronione.',
    'prohibited_if'        => 'Pole :attribute jest zabronione, gdy :other jest :value.',
    'prohibited_unless'    => 'Pole :attribute jest zabronione, chyba że :other znajduje się w :values.',
    'prohibits'            => 'Pole :attribute zabrania obecności pola :other.',
    'regex'                => 'Format pola :attribute jest nieprawidłowy.',
    'required'             => 'Pole :attribute jest wymagane.',
    'required_array_keys'  => 'Pole :attribute musi zawierać wpisy dla: :values.',
    'required_if'          => 'Pole :attribute jest wymagane, gdy :other jest :value.',
    'required_unless'      => 'Pole :attribute jest wymagane, chyba że :other jest w :values.',
    'required_with'        => 'Pole :attribute jest wymagane, gdy :values jest obecne.',
    'required_with_all'    => 'Pole :attribute jest wymagane, gdy :values jest obecne.',
    'required_without'     => 'Pole :attribute jest wymagane, gdy :values nie jest obecne.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy żadne z :values nie jest obecne.',
    'same'                 => 'Pole :attribute i :other muszą być takie same.',
    'size'                 => [
        'numeric' => 'Pole :attribute musi mieć rozmiar :size.',
        'file'    => 'Pole :attribute musi mieć rozmiar :size kilobajtów.',
        'string'  => 'Pole :attribute musi mieć rozmiar :size znaków.',
        'array'   => 'Pole :attribute musi zawierać :size elementów.',
    ],
    'starts_with'          => 'Pole :attribute musi zaczynać się od jednego z podanych: :values.',
    'string'               => 'Pole :attribute musi być ciągiem znaków.',
    'timezone'             => 'Pole :attribute musi być prawidłową strefą czasową.',
    'unique'               => 'Pole :attribute już istnieje.',
    'uploaded'             => 'Nie udało się przesłać pliku :attribute.',
    'url'                  => 'Format pola :attribute jest nieprawidłowy.',
    'uuid'                 => 'Pole :attribute musi być poprawnym UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'wlasna-wiadomosc',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
