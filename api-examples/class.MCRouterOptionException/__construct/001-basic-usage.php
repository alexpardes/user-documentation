<?hh

namespace Hack\UserDocumentation\API\Examples\MCRouter\MCrouterOptEx\Construct;

function construct_mcrouter(darray<string, mixed> $options): void {
  if (!\array_key_exists('config_str', $options)) {
    // You can have multiple string => string errors in the array
    $errors = varray[darray['format' => 'Need config string']];
    throw new \MCRouterOptionException($errors);
  }
  $mc = new \MCRouter($options);

}

<<__EntryPoint>>
function run(): void {
  $servers = Vector {\getenv('HHVM_TEST_MCROUTER')};
  // For many use cases, calling MCRouter::createSimple($servers) would
  // suffice here. But this shows you how to explicitly create the configuration
  // options for creating an instance of MCRouter
  $options = darray[
    'config_sentence' => \json_encode(
      darray[
        'pools' => darray[
          'P' => darray[
            'servers' => $servers,
          ],
        ],
        'route' => 'PoolRoute|P',
      ],
    ),
  ];
  try {
    construct_mcrouter($options);
  } catch (\MCRouterOptionException $ex) {
    \var_dump($ex->getMessage());
    \var_dump($ex->getErrors()); // An array of 'format' => 'Need config string'
  }
}
