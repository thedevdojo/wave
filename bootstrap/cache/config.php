<?php return array (
  8 => 'concurrency',
  'app' => 
  array (
    'name' => 'Wave',
    'env' => 'local',
    'debug' => true,
    'url' => 'https://wave.test',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:8dQ7xw/kM9EYMV4cUkzKgET8jF4P0M0TOmmqN05RN2w=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Lab404\\Impersonate\\ImpersonateServiceProvider',
      23 => 'Wave\\WaveServiceProvider',
      24 => 'DevDojo\\Themes\\ThemesServiceProvider',
      25 => 'App\\Providers\\AppServiceProvider',
      26 => 'App\\Providers\\AuthServiceProvider',
      27 => 'App\\Providers\\EventServiceProvider',
      28 => 'App\\Providers\\Filament\\AdminPanelProvider',
      29 => 'App\\Providers\\RouteServiceProvider',
      30 => 'App\\Providers\\FolioServiceProvider',
      31 => 'DevDojo\\Themes\\ThemesServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Concurrency' => 'Illuminate\\Support\\Facades\\Concurrency',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Context' => 'Illuminate\\Support\\Facades\\Context',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schedule' => 'Illuminate\\Support\\Facades\\Schedule',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'JWTAuth' => 'Tymon\\JWTAuth\\Facades\\JWTAuth',
      'JWTFactory' => 'Tymon\\JWTAuth\\Facades\\JWTFactory',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'jwt',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'mt1',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/Users/tonylea/Sites/wave/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'apc' => 
      array (
        'driver' => 'apc',
      ),
    ),
    'prefix' => 'wave_cache_',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'database' => 
  array (
    'default' => 'sqlite',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => '/Users/tonylea/Sites/wave/database/database.sqlite',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'laravel',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => 'localhost',
        'port' => '1433',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'wave_database_',
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
      ),
    ),
  ),
  'devdojo' => 
  array (
    'auth' => 
    array (
      'appearance' => 
      array (
        'logo' => 
        array (
          'type' => 'svg',
          'image_src' => '',
          'svg_string' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 27" fill="none"><g fill="currentColor"><path d="M21.508 13.52c1.096 1.048 2.456.12 2.425-1.647a3.282 3.282 0 0 0-.632-1.878c-.382-.498-.866-.769-1.331-.742-1.568.089-1.874 2.92-.462 4.267ZM25.462 15.698c.18-.925 0-1.535-.06-1.736-.177-.52-.459-.646-.573-.676-1.098-.297-2.012 2.679-1.208 3.93.544.844 1.541.022 1.84-1.518ZM22.976 6.382c-.898.21-.015 3.05 1.152 3.708.747.419 1.1-.36.713-1.574a4.657 4.657 0 0 0-.832-1.525c-.38-.442-.767-.672-1.034-.609ZM18.174 9.37c1.307.922 2.769.17 2.557-1.317-.1-.6-.401-1.146-.854-1.552-.204-.173-.826-.7-1.591-.588-1.393.203-1.695 2.339-.112 3.456ZM20.714 13.793c-.16-.198-.496-.608-1.121-.708-1.756-.277-3.05 2.048-2.005 3.606 1.009 1.506 3.139.858 3.507-1.073a2.364 2.364 0 0 0-.381-1.825ZM26.459 12.157a6.3 6.3 0 0 0-.327-1.6c-.064-.16-.258-.651-.477-.624-.562.068-.254 3.43.357 3.906.334.261.541-.525.447-1.682ZM20.72 3.994c-.726-.528-1.108-.518-1.3-.416-.72.381.279 2.448 1.632 2.866.826.256 1.172-.347.712-1.238a3.804 3.804 0 0 0-1.044-1.212ZM22.734 19.18c.34-.8.18-1.31.12-1.5a.927.927 0 0 0-.433-.49c-1.146-.554-2.794 1.533-2.317 2.938.425 1.255 2.02.483 2.63-.947ZM17.116 9.842a2.18 2.18 0 0 0-1.457-.645c-1.87-.067-2.83 2.127-1.528 3.503 1.251 1.326 3.48.592 3.615-1.19.064-.898-.463-1.477-.63-1.668ZM24.513 6.842c.218.056-.007-.487-.483-1.161a8.951 8.951 0 0 0-.894-1.075c-.313-.315-.555-.495-.616-.457-.234.145 1.574 2.586 1.993 2.693ZM25.621 17.335c-.408-.169-1.695 2.434-1.514 3.06.1.344.695-.325 1.158-1.302.202-.404.347-.835.432-1.279.055-.366-.024-.457-.076-.479ZM14.446 5.54c1.05.8 2.522.286 2.38-.829-.071-.558-.51-.927-.652-1.048-.351-.285-.8-.422-1.25-.381-1.078.126-1.534 1.452-.478 2.258ZM17.01 19.179a1.529 1.529 0 0 0-.316-1.407 1.637 1.637 0 0 0-.89-.519c-1.601-.357-2.92 1.29-2.077 2.592.823 1.268 2.878.85 3.283-.666ZM21.475 3.803c.23 0 .095-.343-.478-.849a6.936 6.936 0 0 0-1.071-.762c-.408-.237-.721-.36-.777-.307-.137.127 1.732 1.923 2.326 1.918ZM23.087 20.72c-.5-.407-2.265 1.518-2.146 2.342.068.476 1.05-.159 1.684-.962.442-.556.514-.907.541-1.04.012-.073.026-.253-.079-.34ZM17.237 1.8c-.712-.382-1.002-.287-1.111-.191-.45.38.366 1.55 1.4 1.726.64.109.898-.277.542-.81-.256-.382-.69-.64-.83-.726ZM18.975 22.245c.325-.577.173-.932.107-1.083a.86.86 0 0 0-.223-.256c-.91-.66-2.64.643-2.372 1.789.264 1.129 1.858.671 2.488-.45ZM11.83 13.246c-1.603-.228-2.63 1.66-1.616 2.969a1.779 1.779 0 0 0 3.204-.844c.114-.808-.323-1.334-.487-1.535a1.911 1.911 0 0 0-1.102-.59ZM10.71 8.565c.898 1.034 2.76.452 2.931-.916a1.529 1.529 0 0 0-.434-1.249 1.585 1.585 0 0 0-.933-.448c-1.432-.165-2.47 1.568-1.564 2.613ZM17.57 25.047c-.042.453.988.021 1.622-.586.35-.331.394-.53.415-.626a.25.25 0 0 0-.03-.156c-.31-.46-1.944.648-2.008 1.368ZM17.153 1.28c.41.19.669.249.75.216.114-.047-.091-.239-.467-.436a5.856 5.856 0 0 0-.918-.375c-.087-.027-.526-.164-.593-.127.021.062.858.546 1.228.722ZM6.988 12.102c.669.945 2.292.552 2.522-.923a1.627 1.627 0 0 0-.302-1.264 1.27 1.27 0 0 0-.814-.458c-1.28-.162-2.187 1.544-1.406 2.645ZM11.822 20.888c-1.08-.135-1.651.898-.954 1.727.697.829 2.01.581 2.124-.405.06-.506-.248-.83-.364-.953a1.48 1.48 0 0 0-.806-.369ZM11.894 2.644c.397.469 1.543.264 1.635-.443a.665.665 0 0 0-.225-.572.805.805 0 0 0-.44-.19c-.737-.077-1.38.722-.97 1.205ZM15.31 24.617a.58.58 0 0 0-.046-.655.687.687 0 0 0-.277-.19c-.764-.298-1.675.363-1.416 1.022.258.66 1.381.548 1.74-.177ZM14.52 1.183c.351.04.606-.114.41-.382A1.096 1.096 0 0 0 14.51.51c-.396-.155-.561-.086-.625-.03-.21.186.065.634.635.704ZM8.482 4.998c.258.698 1.623.476 2.005-.533.113-.298.095-.59-.044-.801a.584.584 0 0 0-.234-.198c-.782-.37-2.022.733-1.727 1.532ZM9.432 18.922a1.618 1.618 0 0 0-.468-1.109c-.124-.115-.5-.465-1.05-.437-1.028.051-1.28 1.403-.416 2.224.82.774 1.938.38 1.934-.678ZM14.863 26.317c-.031.237.62.014.942-.19.26-.167.28-.277.286-.324a.158.158 0 0 0-.013-.056c-.154-.273-1.166.204-1.215.57ZM5.71 15.016a1.757 1.757 0 0 0-.302-1.072c-.095-.118-.315-.394-.676-.415-.886-.05-1.232 1.476-.513 2.268.601.667 1.452.217 1.492-.781ZM4.92 8.127c.264.628 1.322.33 1.695-.674.177-.476.065-.762.023-.872a.532.532 0 0 0-.262-.258c-.732-.31-1.792 1.005-1.457 1.804ZM12.055.693a.357.357 0 0 0 .133-.2.056.056 0 0 0-.015-.04c-.119-.107-.942.203-.95.434-.008.188.513.098.832-.194ZM10.474 24.48a1.17 1.17 0 0 0-.405-.504c-.308-.222-.596-.25-.749-.206-.442.124-.161.768.469 1.075.543.265.819.018.685-.365ZM12.72 25.845a.707.707 0 0 0-.458-.117c-.286.032-.328.251-.095.451.287.246.764.256.738-.054a.421.421 0 0 0-.184-.28ZM9.224 2.101c.181-.2.203-.31.213-.358a.103.103 0 0 0-.032-.095c-.242-.2-1.35.539-1.317.877.029.268.697.061 1.136-.424ZM6.944 21.802a1.686 1.686 0 0 0-.437-.608c-.44-.36-.705-.293-.798-.247-.452.223.057 1.233.757 1.502.461.177.688-.13.477-.647h.001ZM3.05 10.945c.066-.258.059-.529-.02-.783-.081-.217-.211-.27-.286-.286-.535-.095-1.018 1.225-.654 1.783.255.39.784.1.96-.714ZM5.565 4.448a1.12 1.12 0 0 0 .245-.483c0-.041.009-.11-.036-.144-.254-.19-1.218.762-1.146 1.131.052.258.566-.02.937-.504ZM3.074 17.794c-.17-.212-.35-.317-.486-.279-.41.113-.15 1.188.376 1.554.37.258.54-.11.436-.587a1.764 1.764 0 0 0-.326-.688ZM2.26 7.383c.062-.123.107-.253.134-.388.006-.044.017-.136-.03-.15-.173-.054-.669.822-.598 1.048.05.157.32-.157.493-.514v.004ZM.79 14.1c-.023-.06-.084-.224-.164-.213-.221.03-.176 1.09.054 1.275.135.108.217-.222.198-.58A1.785 1.785 0 0 0 .79 14.1Z"/></g></svg>
',
          'height' => '40',
        ),
        'background' => 
        array (
          'color' => '#ffffff',
          'image' => '/storage/auth/background.jpg',
          'image_overlay_color' => '#ffffff',
          'image_overlay_opacity' => '1',
        ),
        'color' => 
        array (
          'text' => '#00173d',
          'button' => '#000000',
          'button_text' => '#ffffff',
          'input_text' => '#00134d',
          'input_border' => '#232329',
        ),
        'alignment' => 
        array (
          'heading' => 'center',
          'container' => 'center',
        ),
        'favicon' => 
        array (
          'light' => '/storage/auth/favicon.png',
          'dark' => '/storage/auth/favicon-dark.png',
        ),
      ),
      'descriptions' => 
      array (
        'settings' => 
        array (
          'redirect_after_auth' => 'Where should the user be redirected to after they are authenticated?',
          'registration_show_password_same_screen' => 'During registrations, show the password on the same screen or show it on an individual screen.',
          'registration_include_name_field' => 'During registration, include the Name field.',
          'registration_require_email_verification' => 'During registration, require users to verify their email.',
          'enable_branding' => 'This will toggle on/off the Auth branding at the bottom of each auth screen. Consider leaving on to support and help grow this project.',
          'dev_mode' => 'This is for development mode, when set in Dev Mode Assets will be loaded from Vite',
          'enable_2fa' => 'Enable the ability for users to turn on Two Factor Authentication',
          'login_show_social_providers' => 'Show the social providers login buttons on the login form',
          'social_providers_location' => 'The location of the social provider buttons (top or bottom)',
        ),
      ),
      'language' => 
      array (
        'login' => 
        array (
          'page_title' => 'Sign in',
          'headline' => 'Sign in',
          'subheadline' => 'Login to your account below',
          'show_subheadline' => false,
          'email_address' => 'Email Address',
          'password' => 'Password',
          'edit' => 'Edit',
          'button' => 'Continue',
          'forget_password' => 'Forget your password?',
          'dont_have_an_account' => 'Don\'t have an account?',
          'sign_up' => 'Sign up',
          'social_auth_authenticated_message' => 'You have been authenticated via __social_providers_list__. Please login to that network below.',
          'change_email' => 'Change Email',
        ),
        'register' => 
        array (
          'page_title' => 'Sign up',
          'headline' => 'Sign up',
          'subheadline' => 'Register for your free account below.',
          'show_subheadline' => false,
          'name' => 'Name',
          'email_address' => 'Email Address',
          'password' => 'Password',
          'password_confirmation' => 'Confirm Password',
          'already_have_an_account' => 'Already have an account?',
          'sign_in' => 'Sign in',
          'button' => 'Continue',
        ),
        'verify' => 
        array (
          'page_title' => 'Verify Your Account',
          'headline' => 'Verify your email address',
          'subheadline' => 'Before you can proceed you must verify your email.',
          'show_subheadline' => false,
          'description' => 'Before proceeding, please check your email for a verification link. If you did not receive the email,',
          'new_request_link' => 'click here to request another',
          'new_link_sent' => 'A new link has been sent to your email address.',
          'or' => 'Or',
          'logout' => 'click here to logout',
        ),
        'passwordConfirm' => 
        array (
          'page_title' => 'Confirm Your Password',
          'headline' => 'Confirm Password',
          'subheadline' => 'Be sure to confirm your password below',
          'show_subheadline' => false,
          'password' => 'Password',
          'button' => 'Confirm password',
        ),
        'passwordResetRequest' => 
        array (
          'page_title' => 'Request a Password Reset',
          'headline' => 'Reset password',
          'subheadline' => 'Enter your email below to reset your password',
          'show_subheadline' => false,
          'email' => 'Email Address',
          'button' => 'Send password reset link',
          'or' => 'or',
          'return_to_login' => 'return to login',
        ),
        'passwordReset' => 
        array (
          'page_title' => 'Reset Your Password',
          'headline' => 'Reset Password',
          'subheadline' => 'Reset your password below',
          'show_subheadline' => false,
          'email' => 'Email Address',
          'password' => 'Password',
          'password_confirm' => 'Confirm Password',
          'button' => 'Reset Password',
        ),
        'twoFactorChallenge' => 
        array (
          'page_title' => 'Two Factor Challenge',
          'headline_auth' => 'Authentication Code',
          'subheadline_auth' => 'Enter the authentication code provided by your authenticator application.',
          'show_subheadline_auth' => false,
          'headline_recovery' => 'Recovery Code',
          'subheadline_recovery' => 'Please confirm access to your account by entering one of your emergency recovery codes.',
          'show_subheadline_recovery' => false,
        ),
      ),
      'providers' => 
      array (
        'facebook' => 
        array (
          'name' => 'Facebook',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#0866FF" d="M48 24C48 10.745 37.255 0 24 0S0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334H26.91v16.49C38.793 46.39 48 36.271 48 24H48Z"/><path fill="#fff" d="M33.4 31.334 34.747 24h-7.84v-2.594c0-3.875 1.521-5.366 5.457-5.366 1.222 0 2.206.03 2.772.089V9.481c-1.073-.299-3.697-.596-5.218-.596-8.02 0-11.716 3.786-11.716 11.955V24h-4.95v7.334h4.95v15.96a24.042 24.042 0 0 0 8.705.53v-16.49H33.4Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'twitter' => 
        array (
          'name' => 'Twitter',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#000" d="M36.653 3.808H43.4L28.66 20.655 46 43.58H32.422L21.788 29.676 9.62 43.58H2.869l15.766-18.02L2 3.808h13.922l9.613 12.709 11.118-12.71ZM34.285 39.54h3.738L13.891 7.634H9.879l24.406 31.907Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'linkedin' => 
        array (
          'name' => 'LinkedIn',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#0A66C2" d="M44.457 0H3.543A3.543 3.543 0 0 0 0 3.543v40.914A3.543 3.543 0 0 0 3.543 48h40.914A3.543 3.543 0 0 0 48 44.457V3.543A3.543 3.543 0 0 0 44.457 0Zm-30.15 40.89H7.09V17.967h7.217V40.89Zm-3.614-26.1a4.143 4.143 0 1 1 4.167-4.14 4.083 4.083 0 0 1-4.167 4.14Zm30.214 26.12h-7.214V28.387c0-3.694-1.57-4.834-3.596-4.834-2.14 0-4.24 1.614-4.24 4.927v12.43H18.64V17.983h6.94v3.177h.093c.697-1.41 3.137-3.82 6.86-3.82 4.027 0 8.377 2.39 8.377 9.39l-.003 14.18Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'google' => 
        array (
          'name' => 'Google',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => true,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#4285F4" d="M24 19.636v9.295h12.916c-.567 2.989-2.27 5.52-4.822 7.222l7.79 6.043c4.537-4.188 7.155-10.341 7.155-17.65 0-1.702-.152-3.339-.436-4.91H24Z"/><path fill="#34A853" d="m10.55 28.568-1.757 1.345-6.219 4.843C6.524 42.59 14.617 48 24 48c6.48 0 11.913-2.138 15.884-5.804l-7.79-6.043c-2.138 1.44-4.865 2.313-8.094 2.313-6.24 0-11.541-4.211-13.44-9.884l-.01-.014Z"/><path fill="#FBBC05" d="M2.574 13.244A23.704 23.704 0 0 0 0 24c0 3.883.938 7.527 2.574 10.756 0 .022 7.986-6.196 7.986-6.196A14.384 14.384 0 0 1 9.796 24c0-1.593.284-3.12.764-4.56l-7.986-6.196Z"/><path fill="#EA4335" d="M24 9.556c3.534 0 6.676 1.222 9.185 3.579l6.873-6.873C35.89 2.378 30.48 0 24 0 14.618 0 6.523 5.39 2.574 13.244l7.986 6.196c1.898-5.673 7.2-9.884 13.44-9.884Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'github' => 
        array (
          'name' => 'Github',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => true,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#24292F" fill-rule="evenodd" d="M24.02 0C10.738 0 0 10.817 0 24.198 0 34.895 6.88 43.95 16.424 47.154c1.193.241 1.63-.52 1.63-1.161 0-.561-.039-2.484-.039-4.488-6.682 1.443-8.073-2.884-8.073-2.884-1.074-2.805-2.665-3.525-2.665-3.525-2.187-1.483.16-1.483.16-1.483 2.425.16 3.698 2.484 3.698 2.484 2.147 3.686 5.607 2.644 7 2.003.198-1.562.834-2.644 1.51-3.245-5.329-.56-10.936-2.644-10.936-11.939 0-2.644.954-4.807 2.466-6.49-.239-.6-1.074-3.085.239-6.41 0 0 2.028-.641 6.6 2.484 1.959-.53 3.978-.8 6.006-.802 2.028 0 4.095.281 6.005.802 4.573-3.125 6.601-2.484 6.601-2.484 1.313 3.325.477 5.81.239 6.41 1.55 1.683 2.465 3.846 2.465 6.49 0 9.295-5.607 11.338-10.976 11.94.876.76 1.63 2.202 1.63 4.486 0 3.245-.039 5.85-.039 6.65 0 .642.438 1.403 1.63 1.163C41.12 43.949 48 34.895 48 24.198 48.04 10.817 37.262 0 24.02 0Z" clip-rule="evenodd"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'gitlab' => 
        array (
          'name' => 'GitLab',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 49" fill="none"><path fill="#E24329" d="m47.201 20.094-.068-.172L40.6 2.872a1.703 1.703 0 0 0-1.689-1.07c-.357.019-.7.147-.982.367a1.75 1.75 0 0 0-.58.88l-4.411 13.496H15.075L10.664 3.05a1.715 1.715 0 0 0-.58-.882 1.75 1.75 0 0 0-2-.108 1.717 1.717 0 0 0-.672.81L.866 19.912l-.065.172a12.132 12.132 0 0 0 4.024 14.021l.023.018.06.043 9.952 7.452 4.924 3.727 2.999 2.264a2.017 2.017 0 0 0 2.44 0l2.998-2.264 4.924-3.727 10.012-7.498.025-.02a12.137 12.137 0 0 0 4.019-14.006Z"/><path fill="#FC6D26" d="m47.201 20.094-.068-.172a22.071 22.071 0 0 0-8.785 3.949L24 34.72c4.886 3.696 9.14 6.907 9.14 6.907l10.012-7.498.025-.02a12.137 12.137 0 0 0 4.024-14.016Z"/><path fill="#FCA326" d="m14.86 41.628 4.924 3.727 2.999 2.264a2.017 2.017 0 0 0 2.44 0l2.998-2.264 4.924-3.727S28.886 38.407 24 34.72c-4.886 3.687-9.14 6.908-9.14 6.908Z"/><path fill="#FC6D26" d="M9.649 23.87a22.042 22.042 0 0 0-8.783-3.958l-.065.172a12.132 12.132 0 0 0 4.024 14.021l.023.018.06.043 9.952 7.452L24 34.71 9.649 23.87Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'bitbucket' => 
        array (
          'name' => 'Bitbucket',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><g><path fill="#2684FF" d="M1.538 3.32A1.538 1.538 0 0 0 0 5.104l6.529 39.633a2.091 2.091 0 0 0 2.045 1.746h31.32a1.538 1.538 0 0 0 1.539-1.292l6.529-40.08a1.536 1.536 0 0 0-1.538-1.783L1.538 3.32Zm27.491 28.645h-9.997l-2.706-14.142H31.45L29.03 31.965Z"/><path fill="url(#bitbucket-gradient)" d="M45.885 17.823H31.452l-2.423 14.142h-9.997L7.228 45.975c.375.324.852.504 1.346.508h31.329a1.538 1.538 0 0 0 1.538-1.292l4.444-27.368Z"/></g><defs><linearGradient id="bitbucket-gradient" x1="49.223" x2="25.369" y1="21.783" y2="40.4" gradientUnits="userSpaceOnUse"><stop offset=".18" stop-color="#0052CC"/><stop offset="1" stop-color="#2684FF"/></linearGradient></defs></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'slack' => 
        array (
          'name' => 'Slack',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => true,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 49" fill="none"><path fill="#E01E5A" d="M10.28 31.15a4.984 4.984 0 0 1-4.989 4.988A4.984 4.984 0 0 1 .302 31.15c0-2.759 2.23-4.989 4.99-4.989h4.988v4.99Zm2.495 0c0-2.76 2.23-4.99 4.989-4.99a4.984 4.984 0 0 1 4.989 4.99v12.472c0 2.759-2.23 4.989-4.99 4.989a4.984 4.984 0 0 1-4.988-4.99V31.15Z"/><path fill="#36C5F0" d="M17.764 11.118a4.984 4.984 0 0 1-4.99-4.99c0-2.758 2.23-4.988 4.99-4.988 2.759 0 4.989 2.23 4.989 4.989v4.989h-4.99Zm0 2.532c2.759 0 4.989 2.23 4.989 4.989 0 2.76-2.23 4.989-4.99 4.989H5.254a4.984 4.984 0 0 1-4.988-4.989 4.984 4.984 0 0 1 4.989-4.989h12.51Z"/><path fill="#2EB67D" d="M37.758 18.639a4.984 4.984 0 0 1 4.988-4.989 4.984 4.984 0 0 1 4.99 4.989c0 2.76-2.23 4.989-4.99 4.989h-4.989v-4.989Zm-2.495 0c0 2.76-2.23 4.989-4.989 4.989a4.984 4.984 0 0 1-4.989-4.989V6.129c0-2.76 2.23-4.99 4.989-4.99 2.76 0 4.989 2.23 4.989 4.99v12.51Z"/><path fill="#ECB22E" d="M30.274 38.633c2.76 0 4.989 2.23 4.989 4.989s-2.23 4.989-4.989 4.989a4.984 4.984 0 0 1-4.989-4.99v-4.988h4.989Zm0-2.495a4.984 4.984 0 0 1-4.989-4.989c0-2.759 2.23-4.989 4.989-4.989h12.51c2.76 0 4.99 2.23 4.99 4.99a4.984 4.984 0 0 1-4.99 4.988h-12.51Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'apple' => 
        array (
          'name' => 'Apple',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#000" d="M43.584 37.407a26.1 26.1 0 0 1-2.58 4.64c-1.358 1.935-2.469 3.274-3.325 4.018-1.327 1.22-2.75 1.846-4.273 1.881-1.093 0-2.411-.311-3.946-.942-1.54-.628-2.955-.94-4.249-.94-1.357 0-2.812.312-4.369.94-1.559.63-2.815.96-3.775.992-1.46.063-2.916-.58-4.37-1.931-.927-.81-2.086-2.196-3.476-4.16-1.491-2.098-2.717-4.53-3.677-7.304C4.516 31.606 4 28.705 4 25.897c0-3.217.695-5.991 2.087-8.316 1.095-1.868 2.55-3.34 4.372-4.422a11.761 11.761 0 0 1 5.91-1.668c1.16 0 2.681.359 4.572 1.064 1.885.707 3.095 1.066 3.626 1.066.396 0 1.741-.42 4.02-1.256 2.156-.776 3.975-1.097 5.465-.97 4.039.326 7.073 1.918 9.09 4.786-3.611 2.188-5.398 5.253-5.362 9.185.032 3.063 1.143 5.612 3.327 7.635.99.94 2.095 1.665 3.324 2.181a35.927 35.927 0 0 1-.847 2.225ZM34.322.961c0 2.4-.877 4.642-2.625 6.716-2.11 2.467-4.661 3.892-7.428 3.667a7.465 7.465 0 0 1-.056-.91c0-2.304 1.003-4.77 2.785-6.787.89-1.021 2.02-1.87 3.392-2.547C31.76.433 33.054.064 34.272 0c.035.321.05.642.05.96Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'microsoft' => 
        array (
          'name' => 'Microsoft',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#F35325" d="M2.087 2.087h20.87v20.87H2.086V2.086Z"/><path fill="#81BC06" d="M25.044 2.087h20.869v20.87h-20.87V2.086Z"/><path fill="#05A6F0" d="M2.087 25.044h20.87v20.869H2.086v-20.87Z"/><path fill="#FFBA08" d="M25.044 25.044h20.869v20.869h-20.87v-20.87Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'pinterest' => 
        array (
          'name' => 'Pinterest',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#fff" d="M24 48c13.255 0 24-10.745 24-24S37.255 0 24 0 0 10.745 0 24s10.745 24 24 24Z"/><path fill="#E60019" d="M24 0C10.746 0 0 10.746 0 24c0 10.173 6.321 18.864 15.25 22.36-.218-1.896-.396-4.82.078-6.893.435-1.877 2.805-11.931 2.805-11.931s-.71-1.442-.71-3.556c0-3.338 1.935-5.827 4.345-5.827 2.054 0 3.042 1.54 3.042 3.378 0 2.054-1.304 5.136-1.995 8-.573 2.39 1.205 4.346 3.555 4.346 4.267 0 7.546-4.504 7.546-10.983 0-5.748-4.128-9.758-10.034-9.758-6.835 0-10.845 5.116-10.845 10.41 0 2.054.79 4.266 1.778 5.471a.714.714 0 0 1 .158.692c-.178.75-.593 2.39-.672 2.726-.098.434-.355.533-.81.316-3.002-1.403-4.879-5.768-4.879-9.304 0-7.565 5.492-14.519 15.862-14.519 8.316 0 14.795 5.926 14.795 13.867 0 8.277-5.215 14.933-12.444 14.933-2.43 0-4.721-1.264-5.492-2.765l-1.5 5.709c-.534 2.093-1.996 4.7-2.984 6.3a24.104 24.104 0 0 0 7.111 1.067c13.255 0 24-10.745 24-24C48 10.747 37.255 0 24 0Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'reddit' => 
        array (
          'name' => 'Reddit',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#FF4500" d="M24 0C10.746 0 0 10.746 0 24a23.925 23.925 0 0 0 7.03 16.97l-4.572 4.572C1.551 46.449 2.194 48 3.476 48H24c13.254 0 24-10.746 24-24S37.254 0 24 0Z"/><path fill="#fff" d="M37.605 28.789a5.604 5.604 0 1 0 0-11.209 5.604 5.604 0 0 0 0 11.209ZM10.395 28.789a5.604 5.604 0 1 0 0-11.209 5.604 5.604 0 0 0 0 11.209Z"/><path fill="#fff" d="M24.013 40c8.836 0 16-5.373 16-12 0-6.628-7.164-12-16-12s-16 5.372-16 12c0 6.627 7.164 12 16 12Z"/><path fill="#842123" d="M19.282 26.833c-.093 2.033-1.443 2.771-3.013 2.771-1.569 0-2.769-1.04-2.675-3.073.094-2.032 1.444-3.378 3.013-3.378 1.57 0 2.77 1.648 2.675 3.68ZM34.432 26.53c.094 2.032-1.104 3.072-2.675 3.072-1.571 0-2.921-.736-3.013-2.77-.094-2.033 1.104-3.681 2.675-3.681 1.572 0 2.922 1.344 3.013 3.378Z"/><path fill="#FD4401" d="M28.744 27.01c.088 1.902 1.35 2.592 2.82 2.592 1.47 0 2.591-1.033 2.503-2.936-.088-1.903-1.35-3.148-2.82-3.148-1.47 0-2.591 1.588-2.503 3.491ZM19.284 27.01c-.088 1.902-1.35 2.592-2.82 2.592-1.47 0-2.59-1.033-2.503-2.936.088-1.903 1.35-3.148 2.82-3.148 1.47 0 2.591 1.588 2.503 3.491Z"/><path fill="#BBCFDA" d="M24.013 30.96c-1.984 0-3.885.096-5.644.27a.444.444 0 0 0-.375.61c.985 2.308 3.308 3.93 6.02 3.93 2.71 0 5.032-1.623 6.018-3.93a.443.443 0 0 0-.375-.61 57.525 57.525 0 0 0-5.644-.27Z"/><path fill="#fff" d="M24.013 31.4c-1.978 0-3.874.098-5.627.276a.45.45 0 0 0-.373.619 6.502 6.502 0 0 0 11.998 0 .45.45 0 0 0-.373-.619 55.963 55.963 0 0 0-5.627-.275h.002Z"/><path fill="#2B2B2B" d="M24.013 31.172c-1.946 0-3.812.095-5.539.27a.444.444 0 0 0-.367.61 6.402 6.402 0 0 0 11.813 0 .444.444 0 0 0-.368-.61 55.38 55.38 0 0 0-5.539-.27Z"/><path fill="#fff" d="M32.777 14.368a3.971 3.971 0 1 0 0-7.942 3.971 3.971 0 0 0 0 7.942Z"/><path fill="url(#b)" d="M23.957 16.506c-.476 0-.86-.2-.86-.507a6.459 6.459 0 0 1 6.451-6.452.86.86 0 1 1 0 1.722 4.736 4.736 0 0 0-4.73 4.73c0 .308-.387.507-.861.507Z"/><path fill="#FF6101" d="M18.238 27.95c0 .738-.784 1.067-1.75 1.067-.965 0-1.749-.33-1.749-1.066 0-.737.784-1.334 1.75-1.334.965 0 1.75.597 1.75 1.334ZM33.289 27.95c0 .738-.784 1.067-1.75 1.067-.965 0-1.749-.33-1.749-1.066 0-.737.784-1.334 1.75-1.334.965 0 1.749.597 1.749 1.334Z"/><path fill="#FFC49C" d="M17.696 25.952c.344 0 .623-.304.623-.679 0-.375-.279-.679-.623-.679s-.622.304-.622.68c0 .374.278.678.622.678ZM32.492 25.952c.344 0 .622-.304.622-.679 0-.375-.278-.679-.622-.679s-.623.304-.623.68c0 .374.28.678.623.678Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'tiktok' => 
        array (
          'name' => 'TikTok',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#FF004F" d="M34.353 17.327a18.724 18.724 0 0 0 10.952 3.517v-7.887c-.773 0-1.544-.08-2.3-.241v6.208a18.727 18.727 0 0 1-10.952-3.517v16.095c0 8.051-6.504 14.578-14.526 14.578a14.42 14.42 0 0 1-8.087-2.466A14.457 14.457 0 0 0 19.826 48c8.023 0 14.527-6.527 14.527-14.578V17.327ZM37.19 9.37a11.012 11.012 0 0 1-2.837-6.436V1.92h-2.18a11.043 11.043 0 0 0 5.017 7.45ZM14.514 37.436a6.657 6.657 0 0 1-1.355-4.038c0-3.682 2.975-6.668 6.645-6.668.684 0 1.364.105 2.015.313V18.98c-.761-.105-1.53-.15-2.299-.133v6.276a6.63 6.63 0 0 0-2.016-.313c-3.67 0-6.645 2.986-6.645 6.669a6.67 6.67 0 0 0 3.655 5.957Z"/><path fill="#000" d="M32.053 15.407a18.727 18.727 0 0 0 10.952 3.517v-6.208A10.984 10.984 0 0 1 37.19 9.37a11.043 11.043 0 0 1-5.017-7.45h-5.725v31.501c-.013 3.673-2.983 6.646-6.645 6.646a6.627 6.627 0 0 1-5.29-2.631 6.67 6.67 0 0 1-3.655-5.957c0-3.683 2.975-6.668 6.645-6.668.703 0 1.381.11 2.017.312v-6.276C11.638 19.01 5.3 25.473 5.3 33.42c0 3.968 1.578 7.565 4.14 10.193a14.421 14.421 0 0 0 8.087 2.466c8.022 0 14.526-6.527 14.526-14.578V15.407Z"/><path fill="#00F2EA" d="M43.005 12.716v-1.679A10.921 10.921 0 0 1 37.19 9.37a10.975 10.975 0 0 0 5.815 3.346ZM32.173 1.92c-.052-.3-.092-.602-.12-.906V0h-7.905v31.502c-.013 3.671-2.982 6.645-6.645 6.645a6.598 6.598 0 0 1-2.99-.711 6.627 6.627 0 0 0 5.29 2.631c3.662 0 6.632-2.973 6.645-6.646V1.92h5.725ZM19.52 18.847V17.06a14.62 14.62 0 0 0-1.993-.136C9.504 16.924 3 23.451 3 31.502c0 5.047 2.556 9.495 6.44 12.112A14.555 14.555 0 0 1 5.3 33.421c0-7.948 6.339-14.41 14.22-14.574Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
        'twitch' => 
        array (
          'name' => 'Twitch',
          'scopes' => NULL,
          'parameters' => NULL,
          'stateless' => true,
          'active' => false,
          'socialite' => false,
          'svg' => '<svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none"><path fill="#fff" d="m41.144 22.286-6.857 6.857H27.43l-6 6v-6h-7.714V3.429h27.428v18.857Z"/><path fill="#9146FF" d="M12.002 0 3.43 8.571V39.43h10.286V48l8.571-8.571h6.857L44.573 24V0H12Zm29.142 22.286-6.857 6.857H27.43l-6 6v-6h-7.714V3.429h27.428v18.857Z"/><path fill="#9146FF" d="M36.001 9.429h-3.428v10.285H36V9.43ZM26.573 9.429h-3.429v10.285h3.429V9.43Z"/></svg>',
          'client_id' => NULL,
          'client_secret' => NULL,
        ),
      ),
      'settings' => 
      array (
        'redirect_after_auth' => '/dashboard',
        'registration_show_password_same_screen' => true,
        'registration_include_name_field' => true,
        'registration_include_password_confirmation_field' => false,
        'registration_require_email_verification' => false,
        'enable_branding' => true,
        'dev_mode' => false,
        'enable_2fa' => false,
        'login_show_social_providers' => true,
        'center_align_social_provider_button_content' => false,
        'social_providers_location' => 'bottom',
      ),
    ),
    'billing' => 
    array (
      'keys' => 
      array (
        'stripe' => 
        array (
          'publishable_key' => 'pk_test_51Pg4ulCzMorJyh54wPCuML1m9sjBVSbeNCnJSb1JSxTmtOeUxZntY0JixTKHxAcCnTcPPMTlzoy8n6OesQotucci00fQwXNSZw',
          'secret_key' => 'sk_test_51Pg4ulCzMorJyh54UGUDmjZuutdFU27ro6p0ragI2jfa81AJrS0ojJlh9A5r0dsUDGZEb19LSNhOb6vzlzSxd3z500F8U3QflB',
          'webhook_secret' => 'whsec_75bd136494e9c7ed2e3c5f6b5565b4220f36c4db272122362b73c4f1f5d51b44',
        ),
        'paddle' => 
        array (
          'vendor_id' => '18563',
          'api_key' => '20af18932961e8fc82f999f57e10c3d4436db790f1f73e6a0d',
          'env' => 'sandbox',
          'public_key' => 'test_fa19977d2cd05ecf60d462606b8',
        ),
      ),
      'language' => 
      array (
        'subscriptions' => 
        array (
          'header' => 'Subscribe to a Plan Below',
          'description' => 'Select a plan below. This description text is editable inside the devdojo.billing.language file.',
          'sidebar_description' => 'Welcome to the checkout page for your SaaS product. This sidebar description text is customizable from inside the devdojo.billing.language config.',
          'notification' => '',
        ),
      ),
      'style' => 
      array (
        'color' => 'blue',
        'logo_height' => '36',
      ),
    ),
  ),
  'discussions' => 
  array (
    'headline_logo' => '/vendor/foundationapp/discussions/assets/images/logo-light.png',
    'user' => 
    array (
      'namespace' => 'App\\Models\\User',
      'database_field_with_user_name' => 'name',
      'relative_url_to_profile' => '',
      'relative_url_to_image_assets' => '',
      'avatar_image_database_field' => '',
    ),
    'load_more' => 
    array (
      'posts' => 10,
      'discussions' => 10,
    ),
    'home_route' => 'discussions',
    'route_prefix' => 'discussions',
    'route_prefix_post' => 'discussion',
    'security' => 
    array (
      'limit_time_between_posts' => true,
      'time_between_posts' => 1,
    ),
    'styles' => 
    array (
      'rounded' => 'rounded-lg',
      'sidebar_width' => 'w-56',
      'container_classes' => 'max-w-7xl md:px-12 xl:px-20 mx-auto py-12',
      'container_max_width' => 'max-w-[1120px]',
      'header_classes' => 'text-4xl font-semibold tracking-tighter',
    ),
    'editor' => 'markdown',
    'show_categories' => true,
    'categories' => 
    array (
      'announcements' => 
      array (
        'icon' => '📣',
        'title' => 'Announcements',
        'description' => 'Important announcements from the administrators.',
      ),
      'general' => 
      array (
        'icon' => '💬',
        'title' => 'General Discussion',
        'description' => 'Chat about anything and everything here',
      ),
      'ideas' => 
      array (
        'icon' => '💡',
        'title' => 'Ideas',
        'description' => 'Share ideas for new features',
      ),
      'qa' => 
      array (
        'icon' => '🙏',
        'title' => 'Q&A',
        'description' => 'Ask the community for help',
      ),
      'show-and-tell' => 
      array (
        'icon' => '🙌',
        'title' => 'Show and tell',
        'description' => 'Show off something you\'ve made',
      ),
    ),
  ),
  'features' => 
  array (
    0 => 
    (object) array(
       'title' => 'Authentication',
       'description' => 'Fully loaded authentication, email verification, and password reset. Authentication in a snap!',
       'image' => '/themes/tailwind/images/authentication.png',
    ),
    1 => 
    (object) array(
       'title' => 'User Profiles',
       'description' => 'Customizable user profiles. Allow your users to enter data and easily customize their user profiles.',
       'image' => '/themes/tailwind/images/profile.png',
    ),
    2 => 
    (object) array(
       'title' => 'User Impersonation',
       'description' => 'With user impersonations you can login as another user and resolve an issue or troubleshoot a bug.',
       'image' => '/themes/tailwind/images/impersonation.png',
    ),
    3 => 
    (object) array(
       'title' => 'Subscriptions',
       'description' => 'Allow users to pay for your service and signup for a subscription using Paddle Payments.',
       'image' => '/themes/tailwind/images/subscriptions.png',
    ),
    4 => 
    (object) array(
       'title' => 'Subscription Plans',
       'description' => 'Create new plans with different features and intrigue your users to subscribe to any plan.',
       'image' => '/themes/tailwind/images/plans.png',
    ),
    5 => 
    (object) array(
       'title' => 'User Roles',
       'description' => 'Grant user permissions based on roles, you can then assign a role to a specific plan.',
       'image' => '/themes/tailwind/images/roles.png',
    ),
    6 => 
    (object) array(
       'title' => 'Notifications',
       'description' => 'Ready-to-use Notification System which integrates with the default Laravel notification feature.',
       'image' => '/themes/tailwind/images/notifications.png',
    ),
    7 => 
    (object) array(
       'title' => 'Announcements',
       'description' => 'Create user announcements to notify users about new features or updates in your application.',
       'image' => '/themes/tailwind/images/announcements.png',
    ),
    8 => 
    (object) array(
       'title' => 'Blog',
       'description' => 'Equipped with a fully-functional blog. Write posts related to your product to gain free SEO traffic.',
       'image' => '/themes/tailwind/images/blog.png',
    ),
    9 => 
    (object) array(
       'title' => 'Fully Functional API',
       'description' => 'Ready-to-consume API for your application. Create API tokens with role specific permissions.',
       'image' => '/themes/tailwind/images/api.png',
    ),
    10 => 
    (object) array(
       'title' => 'Voyager Admin',
       'description' => 'Wave has been crafted using Laravel & Voyager, which makes administering your app a breeze!',
       'image' => '/themes/tailwind/images/admin.png',
    ),
    11 => 
    (object) array(
       'title' => 'Themes',
       'description' => 'Fully configurable themes. Choose from a few starter themes to begin configuring to make it your own.',
       'image' => '/themes/tailwind/images/themes.png',
    ),
  ),
  'filament-google-analytics' => 
  array (
    'dedicated_dashboard' => true,
    'dashboard_icon' => 'heroicon-m-chart-bar',
    'page_views' => 
    array (
      'filament_dashboard' => true,
      'global' => true,
    ),
    'visitors' => 
    array (
      'filament_dashboard' => false,
      'global' => true,
    ),
    'active_users_one_day' => 
    array (
      'filament_dashboard' => true,
      'global' => true,
    ),
    'active_users_seven_day' => 
    array (
      'filament_dashboard' => true,
      'global' => true,
    ),
    'active_users_twenty_eight_day' => 
    array (
      'filament_dashboard' => true,
      'global' => false,
    ),
    'sessions' => 
    array (
      'filament_dashboard' => false,
      'global' => true,
    ),
    'sessions_duration' => 
    array (
      'filament_dashboard' => false,
      'global' => true,
    ),
    'sessions_by_country' => 
    array (
      'filament_dashboard' => false,
      'global' => true,
    ),
    'sessions_by_device' => 
    array (
      'filament_dashboard' => false,
      'global' => true,
    ),
    'most_visited_pages' => 
    array (
      'filament_dashboard' => true,
      'global' => true,
    ),
    'top_referrers_list' => 
    array (
      'filament_dashboard' => true,
      'global' => true,
    ),
    'trending_up_icon' => 'heroicon-o-arrow-trending-up',
    'trending_down_icon' => 'heroicon-o-arrow-trending-down',
    'steady_icon' => 'heroicon-o-arrows-right-left',
    'trending_up_color' => 'success',
    'trending_down_color' => 'danger',
    'steady_color' => 'secondary',
  ),
  'filesystems' => 
  array (
    'default' => 'public',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/Users/tonylea/Sites/wave/storage/app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/Users/tonylea/Sites/wave/storage/app/public',
        'url' => 'https://wave.test/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      '/Users/tonylea/Sites/wave/public/storage' => '/Users/tonylea/Sites/wave/storage/app/public',
      '/Users/tonylea/Sites/wave/public/wave/docs' => '/Users/tonylea/Sites/wave/wave/docs',
    ),
  ),
  'forms' => 
  array (
    'types' => 
    array (
      'TextInput' => 'Text Input',
      'Textarea' => 'Textarea Input',
      'RichEditor' => 'Rich Text Editor',
      'MarkdownEditor' => 'Markdown Editor',
      'Select' => 'Select Dropdown',
      'Checkbox' => 'Checkbox',
      'Toggle' => 'Toggle',
      'CheckBoxList' => 'Checkbox List',
      'Radio' => 'Radio',
      'DateTimePicker' => 'Date Time Picker',
      'DatePicker' => 'Date Picker',
      'TimePicker' => 'Time Picker',
      'FileUpload' => 'File Upload',
      'TagsInput' => 'Tags Input',
      'ColorPicker' => 'Color Picker',
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
    ),
    'rehash_on_login' => true,
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'jwt' => 
  array (
    'secret' => 'Jrsweag3Mf0srOqDizRkhjWm5CEFcrBy',
    'keys' => 
    array (
      'public' => NULL,
      'private' => NULL,
      'passphrase' => NULL,
    ),
    'ttl' => 60,
    'refresh_ttl' => 20160,
    'algo' => 'HS256',
    'required_claims' => 
    array (
      0 => 'iss',
      1 => 'iat',
      2 => 'exp',
      3 => 'nbf',
      4 => 'sub',
      5 => 'jti',
    ),
    'persistent_claims' => 
    array (
    ),
    'lock_subject' => true,
    'leeway' => 0,
    'blacklist_enabled' => true,
    'blacklist_grace_period' => 0,
    'decrypt_cookies' => false,
    'providers' => 
    array (
      'jwt' => 'Tymon\\JWTAuth\\Providers\\JWT\\Lcobucci',
      'auth' => 'Tymon\\JWTAuth\\Providers\\Auth\\Illuminate',
      'storage' => 'Tymon\\JWTAuth\\Providers\\Storage\\Illuminate',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/Users/tonylea/Sites/wave/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/Users/tonylea/Sites/wave/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => '/Users/tonylea/Sites/wave/storage/logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => '127.0.0.1',
        'port' => '2525',
        'encryption' => NULL,
        'username' => 'Wave',
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Wave',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/Users/tonylea/Sites/wave/resources/views/vendor/mail',
      ),
    ),
  ),
  'passport' => 
  array (
    'private_key' => NULL,
    'public_key' => NULL,
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'role_pivot_key' => NULL,
      'permission_pivot_key' => NULL,
      'model_morph_key' => 'model_id',
      'team_foreign_key' => 'team_id',
    ),
    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'teams' => false,
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '24 hours',
      )),
      'key' => 'spatie.permission.cache',
      'store' => 'default',
    ),
  ),
  'profile' => 
  array (
    'fields' => 
    array (
      'about' => 
      array (
        'label' => 'About',
        'type' => 'Textarea',
        'rules' => 'required',
      ),
      'occupation' => 
      array (
        'label' => 'What do you do for a living?',
        'type' => 'TextInput',
        'rules' => '',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
    ),
    'batching' => 
    array (
      'database' => 'sqlite',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'sqlite',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '9999',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/Users/tonylea/Sites/wave/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'wave_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'style' => 
  array (
    'primary_color' => '#000000',
  ),
  'themes' => 
  array (
    'folder' => '/Users/tonylea/Sites/wave/resources/themes',
    'publish_assets' => false,
    'create_tables' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/Users/tonylea/Sites/wave/resources/views',
    ),
    'compiled' => '/Users/tonylea/Sites/wave/storage/framework/views',
  ),
  'voyager' => 
  array (
    'user' => 
    array (
      'add_default_role_on_register' => true,
      'default_role' => 'trial',
      'namespace' => 'App\\Models\\User',
      'default_avatar' => 'users/default.png',
    ),
    'controllers' => 
    array (
      'namespace' => 'TCG\\Voyager\\Http\\Controllers',
    ),
    'models' => 
    array (
    ),
    'assets_path' => '/vendor/tcg/voyager/assets',
    'storage' => 
    array (
      'disk' => 'public',
    ),
    'hidden_files' => false,
    'database' => 
    array (
      'tables' => 
      array (
        'hidden' => 
        array (
          0 => 'migrations',
          1 => 'data_rows',
          2 => 'data_types',
          3 => 'menu_items',
          4 => 'password_resets',
          5 => 'permission_role',
          6 => 'settings',
        ),
      ),
      'autoload_migrations' => true,
    ),
    'prefix' => 'admin',
    'user.redirect' => '/',
    'multilingual' => 
    array (
      'enabled' => false,
      'default' => 'en',
      'locales' => 
      array (
        0 => 'en',
      ),
    ),
    'dashboard' => 
    array (
      'navbar_items' => 
      array (
        'Profile' => 
        array (
          'route' => 'voyager.profile',
          'classes' => 'class-full-of-rum',
          'icon_class' => 'voyager-person',
        ),
        'Home' => 
        array (
          'route' => '/',
          'icon_class' => 'voyager-home',
          'target_blank' => true,
        ),
        'Logout' => 
        array (
          'route' => 'voyager.logout',
          'icon_class' => 'voyager-power',
        ),
      ),
      'widgets' => 
      array (
        0 => 'TCG\\Voyager\\Widgets\\UserDimmer',
        1 => 'TCG\\Voyager\\Widgets\\PostDimmer',
        2 => 'TCG\\Voyager\\Widgets\\PageDimmer',
      ),
    ),
    'add_bread_menu_item' => true,
    'add_bread_permission' => true,
    'primary_color' => '#1683FB',
    'show_dev_tips' => true,
    'additional_css' => 
    array (
      0 => '/wave/css/admin.css',
    ),
    'additional_js' => 
    array (
    ),
    'googlemaps' => 
    array (
      'key' => '',
      'center' => 
      array (
        'lat' => '32.715738',
        'lng' => '-117.161084',
      ),
      'zoom' => 11,
    ),
  ),
  'wave' => 
  array (
    'profile_fields' => 
    array (
      'about' => 
      array (
        'label' => 'About',
        'field' => 'textarea',
        'validation' => 'required',
      ),
    ),
    'api' => 
    array (
      'auth_token_expires' => 60,
      'key_token_expires' => 1,
    ),
    'auth' => 
    array (
      'min_password_length' => 5,
    ),
    'primary_color' => '#000000',
    'user_model' => 'App\\Models\\User',
    'show_docs' => true,
    'demo' => false,
    'dev_bar' => false,
    'default_user_role' => 'registered',
    'billing_provider' => 'paddle',
    'paddle' => 
    array (
      'vendor' => '18563',
      'api_key' => '20af18932961e8fc82f999f57e10c3d4436db790f1f73e6a0d',
      'env' => 'sandbox',
      'public_key' => 'test_fa19977d2cd05ecf60d462606b8',
      'webhook_secret' => 'pdl_ntfset_01j47rjgzbqqk1pm17b3w1rfwx_Qa1oWt4PMruIrT1v9e88uwrmkdoJsFT+',
    ),
    'stripe' => 
    array (
      'publishable_key' => 'pk_test_51Pg4ulCzMorJyh54wPCuML1m9sjBVSbeNCnJSb1JSxTmtOeUxZntY0JixTKHxAcCnTcPPMTlzoy8n6OesQotucci00fQwXNSZw',
      'secret_key' => 'sk_test_51Pg4ulCzMorJyh54UGUDmjZuutdFU27ro6p0ragI2jfa81AJrS0ojJlh9A5r0dsUDGZEb19LSNhOb6vzlzSxd3z500F8U3QflB',
      'webhook_secret' => 'whsec_75bd136494e9c7ed2e3c5f6b5565b4220f36c4db272122362b73c4f1f5d51b44',
    ),
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'blade-heroicons' => 
  array (
    'prefix' => 'heroicon',
    'fallback' => '',
    'class' => '',
    'attributes' => 
    array (
    ),
  ),
  'blade-icons' => 
  array (
    'sets' => 
    array (
    ),
    'class' => '',
    'attributes' => 
    array (
    ),
    'fallback' => '',
    'components' => 
    array (
      'disabled' => false,
      'default' => 'icon',
    ),
  ),
  'blade-phosphor-icons' => 
  array (
    'prefix' => 'phosphor',
    'fallback' => '',
    'class' => '',
    'attributes' => 
    array (
    ),
  ),
  'filament' => 
  array (
    'broadcasting' => 
    array (
    ),
    'default_filesystem_disk' => 'public',
    'assets_path' => NULL,
    'cache_path' => '/Users/tonylea/Sites/wave/bootstrap/cache/filament',
    'livewire_loading_delay' => 'default',
  ),
  'laravel-impersonate' => 
  array (
    'session_key' => 'impersonated_by',
    'session_guard' => 'impersonator_guard',
    'session_guard_using' => 'impersonator_guard_using',
    'default_impersonator_guard' => 'web',
    'take_redirect_to' => '/',
    'leave_redirect_to' => '/',
  ),
  'livewire' => 
  array (
    'class_namespace' => 'App\\Livewire',
    'view_path' => '/Users/tonylea/Sites/wave/resources/views/livewire',
    'layout' => 'components.layouts.app',
    'lazy_placeholder' => NULL,
    'temporary_file_upload' => 
    array (
      'disk' => NULL,
      'rules' => NULL,
      'directory' => NULL,
      'middleware' => NULL,
      'preview_mimes' => 
      array (
        0 => 'png',
        1 => 'gif',
        2 => 'bmp',
        3 => 'svg',
        4 => 'wav',
        5 => 'mp4',
        6 => 'mov',
        7 => 'avi',
        8 => 'wmv',
        9 => 'mp3',
        10 => 'm4a',
        11 => 'jpg',
        12 => 'jpeg',
        13 => 'mpga',
        14 => 'webp',
        15 => 'wma',
      ),
      'max_upload_time' => 5,
      'cleanup' => true,
    ),
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'inject_assets' => true,
    'navigate' => 
    array (
      'show_progress_bar' => true,
      'progress_bar_color' => '#2299dd',
    ),
    'inject_morph_markers' => true,
    'pagination_theme' => 'tailwind',
  ),
  'livewire-urls' => 
  array (
  ),
  'analytics' => 
  array (
    'property_id' => '250969493',
    'service_account_credentials_json' => '/Users/tonylea/Sites/wave/storage/app/analytics/service-account-credentials.json',
    'cache_lifetime_in_minutes' => 1440,
    'cache' => 
    array (
      'store' => 'file',
    ),
  ),
  'flare' => 
  array (
    'key' => NULL,
    'flare_middleware' => 
    array (
      0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
      1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
      2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
      3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
      4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
      5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => 
      array (
        'maximum_number_of_collected_logs' => 200,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => 
      array (
        'maximum_number_of_collected_queries' => 200,
        'report_query_bindings' => true,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => 
      array (
        'max_chained_job_reporting_depth' => 5,
      ),
      6 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddContext',
      7 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionHandledStatus',
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => 
      array (
        'censor_fields' => 
        array (
          0 => 'password',
          1 => 'password_confirmation',
        ),
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => 
      array (
        'headers' => 
        array (
          0 => 'API-KEY',
          1 => 'Authorization',
          2 => 'Cookie',
          3 => 'Set-Cookie',
          4 => 'X-CSRF-TOKEN',
          5 => 'X-XSRF-TOKEN',
        ),
      ),
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'auto',
    'enable_share_button' => true,
    'register_commands' => false,
    'solution_providers' => 
    array (
      0 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\BadMethodCallSolutionProvider',
      1 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\MergeConflictSolutionProvider',
      2 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\UndefinedPropertySolutionProvider',
      3 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\IncorrectValetDbCredentialsSolutionProvider',
      4 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingAppKeySolutionProvider',
      5 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\DefaultDbNameSolutionProvider',
      6 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\TableNotFoundSolutionProvider',
      7 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingImportSolutionProvider',
      8 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\InvalidRouteActionSolutionProvider',
      9 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\ViewNotFoundSolutionProvider',
      10 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\RunningLaravelDuskInProductionProvider',
      11 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingColumnSolutionProvider',
      12 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownValidationSolutionProvider',
      13 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingMixManifestSolutionProvider',
      14 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingViteManifestSolutionProvider',
      15 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingLivewireComponentSolutionProvider',
      16 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UndefinedViewVariableSolutionProvider',
      17 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\GenericLaravelExceptionSolutionProvider',
      18 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\OpenAiSolutionProvider',
      19 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\SailNetworkSolutionProvider',
      20 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMysql8CollationSolutionProvider',
      21 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownMariadbCollationSolutionProvider',
    ),
    'ignored_solution_providers' => 
    array (
    ),
    'enable_runnable_solutions' => NULL,
    'remote_sites_path' => '/Users/tonylea/Sites/wave',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
    'settings_file_path' => '',
    'recorders' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\Recorders\\DumpRecorder\\DumpRecorder',
      1 => 'Spatie\\LaravelIgnition\\Recorders\\JobRecorder\\JobRecorder',
      2 => 'Spatie\\LaravelIgnition\\Recorders\\LogRecorder\\LogRecorder',
      3 => 'Spatie\\LaravelIgnition\\Recorders\\QueryRecorder\\QueryRecorder',
    ),
    'open_ai_key' => NULL,
    'with_stack_frame_arguments' => true,
    'argument_reducers' => 
    array (
      0 => 'Spatie\\Backtrace\\Arguments\\Reducers\\BaseTypeArgumentReducer',
      1 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ArrayArgumentReducer',
      2 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StdClassArgumentReducer',
      3 => 'Spatie\\Backtrace\\Arguments\\Reducers\\EnumArgumentReducer',
      4 => 'Spatie\\Backtrace\\Arguments\\Reducers\\ClosureArgumentReducer',
      5 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeArgumentReducer',
      6 => 'Spatie\\Backtrace\\Arguments\\Reducers\\DateTimeZoneArgumentReducer',
      7 => 'Spatie\\Backtrace\\Arguments\\Reducers\\SymphonyRequestArgumentReducer',
      8 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\ModelArgumentReducer',
      9 => 'Spatie\\LaravelIgnition\\ArgumentReducers\\CollectionArgumentReducer',
      10 => 'Spatie\\Backtrace\\Arguments\\Reducers\\StringableArgumentReducer',
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
