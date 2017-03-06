# log viewer
Logs management module. Log files with all rotations are now available via vault panel, you can view them and, if needed, remove.

[![Latest Stable Version](https://poser.pugx.org/spiral/log-viewer/v/stable)](https://packagist.org/packages/spiral/log-viewer) 
[![Total Downloads](https://poser.pugx.org/spiral/log-viewer/downloads)](https://packagist.org/packages/spiral/log-viewer) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiral-modules/log-viewer/badges/quality-score.png)](https://scrutinizer-ci.com/g/spiral-modules/log-viewer/) 
[![Coverage Status](https://coveralls.io/repos/github/spiral-modules/log-viewer/badge.svg)](https://coveralls.io/github/spiral-modules/log-viewer)
[![Build Status](https://travis-ci.org/spiral-modules/log-viewer.svg?branch=master)](https://travis-ci.org/spiral-modules/log-viewer)

## Installation
```
composer require spiral/log-viewer
spiral register spiral/log-viewer
```

### Include logs link into navigation menu (optional)

Be sure that you have `navigation.vault` placeholder in `modules/vault` config like this
```php
'vault'    => [
    'title' => 'Vault',
    'icon'  => 'power_settings_new',
    'items' => [
        /*{{navigation.vault}}*/
    ]
]
```

#todo-list
1. Add charts/widgets
2. Add listing dependency
3. Tests
