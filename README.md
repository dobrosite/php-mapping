# Отображение данных на структуры PHP

Набор компонентов для отображения простых и структурированных данных на структуры PHP.

## Типы данных

Классы типов данных предназначены для преобразования данных из внешнего представления во внутреннее
и наоборот. Они могут быть использованы как совместно с другими компонентами, так и сами по себе.

### BooleanType

Преобразовывает значение в булев тип.

```php
use DobroSite\Mapping;

$type = new Mapping\BooleanType();
$type->toPhpValue('true'); // true

$type = new Mapping\FloatType(true: 'да', false: 'нет');
$type->toPhpValue('Нет'); // false
```

### ClassType

Используется для отображения данных на объект.

Конструктору требуются следующие аргументы.

- `targetClassResolver` — определитель класса создаваемых объектов (см. `TargetClassResolver` ниже). 

```php
use App\SomeClass;
use DobroSite\Mapping;

$class = new Mapping\ClassType(
    new Mapping\ClassType\ClassName(SomeClass::class),
    new Mapping\ClassType\Properties(
        new Mapping\ClassType\Property(
            propertyName: 'foo'
        ),
        new Mapping\ClassType\Property(
            propertyName: 'bar'
        ),
    ),
);

$object = $class->toPhpType(['foo' => 'FOO', 'bar' => 'BAR']);
// $object → экземпляр SomeClass
// $object->foo → 'FOO' 
// $object->bar → 'BAR' 
```

#### Определители имён классов

Классы с интерфейсом [TargetClassResolver](src/ClassType/TargetClassResolver.php) используются для
определения имени класса создаваемых объектов.

«Из коробки» доступны следующие определители.

##### ClassName

Всегда возвращает одно и то же, заданное в его конструкторе имя класса.

```php
new Mapping\ClassType\ClassName(SomeClass::class)
```

#### Свойства

Список отображаемых свойств задаётся с помощью класса [Properties](src/ClassType/Properties.php),
в конструктор которого надо передать экземпляр [Property](src/ClassType/Property.php) для
каждого отображаемого свойства.

**Аргументы Property**

- `propertyName` — имя свойства в целевом классе.
- `dataName` — имя свойства во входных данных. Если не указано, будет совпадать с `propertyName`.
- `type` — тип свойства. Если не указан, будет использован [SameType](#sametype).
- `defaultValue` — значение по умолчанию (необязательно).

```php
$class = new Mapping\ClassType(
    new Mapping\ClassType\ClassName(SomeClass::class),
    new Mapping\ClassType\Properties(
        new Mapping\ClassType\Property(
            propertyName: 'name',
            dataName: 'Person Name',
            defaultValue: 'Foo',
        ),
        new Mapping\ClassType\Property(
            propertyName: 'address',
            dataName: 'Address',
            type: new Mapping\ClassType(
                new Mapping\ClassType\ClassName(Address::class),
                new Mapping\ClassType\Properties(/* … */)
            )
        ),
    ),
);
```

#### Фабрики объектов

Фабрики объектов — классы с интерфейсом [ObjectFactory](src/ClassType/ObjectFactory.php) —
определяют как именно создавать объекты.

Доступны следующие определители.

##### DefaultObjectFactory

Создаёт объекты обычным способом — с помощью оператора `new`. Если у класса есть конструктор, он
будет вызван и в него будут переданы необходимые аргументы.

##### CallableObjectFactory

Позволяет задать произвольную фабрику в виде значения типа `callable`.

```php
new Mapping\ClassType\CallableObjectFactory([SomeFactory::class, 'create']);
new Mapping\ClassType\CallableObjectFactory([$someFactory, 'create']);
new Mapping\ClassType\CallableObjectFactory('some_factory');
new Mapping\ClassType\CallableObjectFactory(fn() => new SomeClass());
```

### CollectionType

Коллекция (массив) из значений любого другого типа.

```php
use DobroSite\Mapping;

$itemType = new Mapping\FloatType();
$type = new Mapping\CollectionType($itemType);
$type->toPhpValue(['123.45', '54.321']); // [123.45, 54.321]
```

### CustomType

Позволяет задать собственные произвольные правила преобразования с помощью функции.

```php
use DobroSite\Mapping;

$type = new Mapping\CustomType('strtoupper');
$type->toPhpValue('foo'); // 'FOO'

$type = new Mapping\CustomType(fn(array $value): string => $value['foo']);
$type->toPhpValue(['foo' => 'bar']); // 'bar'
```

### EnumType

Преобразовывает значения перечисляемых типов.

```php
use App\SomeEnum;
use DobroSite\Mapping;

$type = new Mapping\EnumType(SomeEnum::class);
$type->toPhpValue('foo'); // SomeEnum::Foo
```

### FloatType

Преобразовывает значение в вещественное число.

```php
use DobroSite\Mapping;

$type = new Mapping\FloatType();
$type->toPhpValue('1234.56'); // 1_234.56

$type = new Mapping\FloatType(new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE));
$type->toPhpValue('1 234,56'); // 1_234.56
```

### MapType

Преобразовывает значение на основе карты (ассоциативного массива).

```php
use DobroSite\Mapping;

$type = new Mapping\MapType(['foo' => 'bar', 'bar' => 'baz']);
$type->toPhpValue('foo'); // 'bar'
$type->toPhpValue('bar'); // 'baz'
```

### NullableType

Модификатор для других типов, разрешающий им принимать значение `null`.

```php
use DobroSite\Mapping;

$mainType = new Mapping\MapType(['foo' => 'bar']);
$type = new Mapping\NullableType($mainType);
$type->toPhpValue('foo'); // 'bar'
$type->toPhpValue(null); // NULL
```

### SameType

Не выполняет никаких преобразований. Пригоден для простых типов: строк, чисел.

```php
use DobroSite\Mapping;

$type = new Mapping\SameType();
$type->toPhpValue('foo'); // 'foo'
$type->toPhpValue(123); // 123
```

## Значения по умолчанию

### DefaultValue

Позволяет задать значение по умолчанию, например, для свойства объекта. Значение должно задаваться
в формате исходных данных.

```php
use DobroSite\Mapping;

new Mapping\ClassType(
    new Mapping\ClassType\ClassName(SomeClass::class),
    new Mapping\ClassType\Properties(
        new Mapping\ClassType\Property(
            propertyName: 'foo',
            type: new ClassType(
                new ClassType\ClassName(OtherClass::class),
                new ClassType\Properties(/* … */),
            ),
            defaultValue: new DefaultValue([]), // ← Значение по умолчанию для свойства «foo».
        )
    )
);
```
