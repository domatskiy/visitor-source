# visitor-source

## install 

```
composer require domatskiy/visitor-source
```
## use

```php
use Domatskiy\VisitorSource;

$VisitorSource = VisitorSource::getInstance();
$source = $VisitorSource->getSource();

if($source->getSource() == VisitorSource\Source::SOURCE_CONTEXT)
{
    // is context
}

```