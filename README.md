# TheGuardianBundle

A Symfony bundle to interact with The Guardian API  via the [The-Guardian-API library](nonsoniyi/the-guardian-api-client).

Still under development, feedback welcome!  

## Quickstart
```bash
symfony new the-guardian-demo --webapp && cd the-guardian-demo
composer require survos/the-guardian-bundle
```

composer config repositories.survos_guardian_bundle '{"type": "path", "url": "../survos/packages/the-guardian-bundle"}'
composer req survos/the-guardian-bundle:"*@dev"



## Installation

Go to https://theGuardian.com and get a key.

Create a new Symfony project.

```bash
symfony new the-guardian-demo --webapp && cd the-guardian-demo
composer require survos/the-guardian-bundle
bin/console the-guardian:list
```

You can browse interactively with the basic admin controller.

```bash
composer require survos/simple-datatables-bundle
symfony server:start -d
symfony open:local --path=/the-guardian/
```

Or edit .env.local and add your API key.


```bash
bin/console the-guardian:list 
```

```bash
+------------- museado/ -----+--------+
| ObjectName     | Path      | Length |
+----------------+-----------+--------+
| photos finales | /museado/ | 0      |
+----------------+-----------+--------+


```

