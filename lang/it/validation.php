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

    'accepted' => 'Il :attribute deve essere accettato.',
    'active_url' => 'Il :attribute non è un URL valido.',
    'after' => 'Il :attribute deve essere una data successiva a :date.',
    'after_or_equal' => 'Il :attribute deve essere una data uguale o successiva a :date.',
    'alpha' => 'Il :attribute può contenere solo lettere.',
    'alpha_dash' => 'Il :attribute può contenere solo lettere, numeri e trattini.',
    'alpha_num' => 'Il :attribute può contenere solo lettere e numeri.',
    'array' => 'Il :attribute deve essere un array.',
    'before' => 'Il :attribute deve essere una data precedente a :date.',
    'before_or_equal' => 'Il :attribute deve essere una data precedente o uguale a :date.',
    'between' => [
        'numeric' => 'Il :attribute deve essere compreso tra :min e :max.',
        'file' => 'Il :attribute deve essere compreso tra :min e :max kilobyte.',
        'string' => 'Il :attribute deve essere compreso tra :min e :max caratteri.',
        'array' => 'Il :attribute deve essere compreso tra :min e :max elementi.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'confirmed' => 'La conferma di :attribute non corrisponde.',
    'date' => 'Il :attribute non è una data valida.',
    'date_equals' => 'Il :attribute deve essere una data uguale a :date.',
    'date_format' => 'Il :attribute non corrisponde al formato :format.',
    'different' => 'Il :attribute e :other devono essere differenti.',
    'digits' => 'Il :attribute deve essere :digits cifre.',
    'digits_between' => 'Il :attribute deve essere compreso tra :min e :max cifre.',
    'dimensions' => 'Il :attribute ha dimensioni immagine non valide.',
    'distinct' => 'Il campo :attribute ha un valore duplicato.',
    'email' => 'Il :attribute deve essere un indirizzo e-mail valido.',
    'ends_with' => 'Il :attribute deve finire con uno dei seguenti: :values.',
    'exists' => 'Il :attribute selezionato non è valido.',
    'file' => 'Il :attribute deve essere un file.',
    'filled' => 'Il campo :attribute è richiesto.',
    'gt' => [
        'numeric' => 'Il :attribute deve essere maggiore di :value.',
        'file' => 'Il :attribute deve essere maggiore di :value kilobyte.',
        'string' => 'Il :attribute deve essere maggiore di :value caratteri.',
        'array' => 'Il :attribute deve avere più di :value elementi.',
    ],
    'gte' => [
        'numeric' => 'Il :attribute deve essere maggiore o uguale a :value.',
        'file' => 'Il :attribute deve essere maggiore o uguale a :value kilobyte.',
        'string' => 'Il :attribute deve essere maggiore o uguale a :value caratteri.',
        'array' => 'Il :attribute deve avere :value items or more.',
    ],
    'image' => 'Il :attribute deve essere una immagine.',
    'in' => 'Il :attribute selezionato non è valido.',
    'in_array' => 'Il campo :attribute non esiste in in :other.',
    'integer' => 'Il :attribute deve essere un intero.',
    'ip' => 'Il :attribute deve essere un indirizzo IP valido.',
    'ipv4' => 'Il :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => 'Il :attribute deve essere un indirizzo IPv6 valido.',
    'json' => 'Il :attribute deve essere una stringa JSON valida.',
    'lt' => [
        'numeric' => 'Il :attribute deve essere meno di :value.',
        'file' => 'Il :attribute deve essere meno di :value kilobyte.',
        'string' => 'Il :attribute deve essere meno di :value caratteri.',
        'array' => 'Il :attribute deve avere meno di :value elementi.',
    ],
    'lte' => [
        'numeric' => 'Il :attribute deve essere uguale o minore di :value.',
        'file' => 'Il :attribute deve essere uguale o minore di :value kilobyte.',
        'string' => 'Il :attribute deve essere uguale o minore di :value caratteri.',
        'array' => 'Il :attribute non può avere più di :value elementi.',
    ],
    'max' => [
        'numeric' => 'Il :attribute non può essere maggiore di :max.',
        'file' => 'Il :attribute non può essere maggiore di :max kilobyte.',
        'string' => 'Il :attribute non può essere maggiore di :max caratteri.',
        'array' => 'Il :attribute non può avere più di :max elemento.',
    ],
    'mimes' => 'Il :attribute deve essere un file di tipo: :values.',
    'mimetypes' => 'Il :attribute must be a file di uno dei seguenti tipi: :values.',
    'min' => [
        'numeric' => 'Il :attribute deve essere almeno :min.',
        'file' => 'Il :attribute deve essere almeno :min kilobyte.',
        'string' => 'Il :attribute deve essere almeno :min caratteri.',
        'array' => 'Il :attribute deve avere almeno :min elementi.',
    ],
    'multiple_of' => 'Il :attribute deve essere un multiplo di :value.',
    'not_in' => 'Il :attribute selezionato non è valido.',
    'not_regex' => 'Il formato di :attribute non è valido.',
    'numeric' => 'Il :attribute deve essere un numero.',
    'password' => 'La password non è corretta.',
    'present' => 'Il campo :attribute deve essere presente.',
    'regex' => 'Il formato di :attribute non è valido.',
    'required' => 'Il campo :attribute è richiesto.',
    'required_if' => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless' => 'Il campo :attribute è richiesto a meno che :other è in :values.',
    'required_with' => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è richiesto quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è richiesto quando :values non sono presenti.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessun :values è presente.',
    'prohibited' => 'Il campo :attribute è proibito.',
    'prohibited_if' => 'Il campo :attribute è proibito quando :other è :value.',
    'prohibited_unless' => 'Il campo :attribute è proibito a meno che :other è in :values.',
    'same' => 'Il :attribute e :other devono corrispondere.',
    'size' => [
        'numeric' => 'Il :attribute deve essere :size.',
        'file' => 'Il :attribute deve essere :size kilobyte.',
        'string' => 'Il :attribute deve essere :size caratteri.',
        'array' => 'Il :attribute deve contenere :size elementi.',
    ],
    'starts_with' => 'Il :attribute deve cominciare con uno dei seguenti: :values.',
    'string' => 'Il :attribute deve essere una stringa.',
    'timezone' => 'Il :attribute deve essere una zona valida.',
    'unique' => 'Il :attribute è già stato preso.',
    'uploaded' => 'Non è stato possibile caricare :attribute.',
    'url' => 'Il formato :attribute non è valido.',
    'uuid' => 'Il :attribute deve essere un UUID valido.',

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
            'rule-name' => 'custom-message',
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
