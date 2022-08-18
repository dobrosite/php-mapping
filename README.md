# Отображение данных на структуры PHP

Набор компонентов для отображения простых и структурированных данных на структуры PHP.

## Типы данных

Классы типов данных предназначены для преобразования данных из внешнего представления во внутреннее
и наоборот. Они могут быть использованы как совместно с другими компонентами, так и сами по себе.

### SameType

Не выполняет никаких преобразований. Пригоден для простых типов: строк, чисел.

```php
use DobroSite\Mapping;

$type = new Mapping\Type\SameType();
$type->toPhpValue('foo'); // 'foo'
$type->toPhpValue(123); // 123
```

### EnumType

Преобразовывает значения перечисляемых типов.


```php
use App\SomeEnum;
use DobroSite\Mapping;

$type = new Mapping\Type\EnumType(SomeEnum::class);
$type->toPhpValue('foo'); // SomeEnum::Foo
```
