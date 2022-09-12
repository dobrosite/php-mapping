# Отображение данных на структуры PHP

Библиотека компонентов для отображения структурированных данных на структуры PHP и обратно.

Основная идея библиотеки — предоставить «кирпичики» из которых можно построить свои правила
отображения данных для любой ситуации.

## Основы

- **Входные данные** (**input**) — структурированные данные, которые требуется отобразить на
  структуры PHP.
- **Выходные данные** (**output**) — структурированные данные, получаемые из структур PHP.
- **Массив** (**array**) — этим словом в библиотеке обозначаются **только ассоциативные массивы**.
- **Коллекция** (**collection**) — индексированный (неассоциативный) массив однотипных значений.

## Mapper

Сердцем библиотеки является интерфейс [Mapper](src/Mapper.php), содержащий всего два метода:

```injectablephp
public function input(mixed $source): mixed;
```

Отображает входные данные `$source` на структуру PHP и возвращает её. 

```injectablephp
public function output(mixed $source): mixed;
```

Выполняет обратное действие.

## Справочник классов

### ArrayDefaults

Позволяет задать значения по умолчанию для ключей, отсутствующих во входном массиве.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\ArrayDefaults([
  'bar' => 'bar value',
]);

$mapper->input(['foo' => 'foo value']);
// ['foo' => 'foo value', 'bar' => 'bar value']
```

### ArrayKeys

Применяет указанное преобразование последовательно к каждому ключу ассоциативного массива. 

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\ArrayKeys(
  new Mapping\Callback(
    input: strtolower(...),
    output: strtoupper(...),
  ),
);

$mapper->input(['FOO' => 'foo value', 'BAR' => 'bar value']);
// ['foo' => 'foo value', 'bar' => 'bar value']

$mapper->output(['foo' => 'foo value', 'bar' => 'bar value']);
// ['FOO' => 'foo value', 'BAR' => 'bar value']
```

### ArrayKeysMap

Меняет имена ключей массива на основе карты соответствия.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\ArrayKeysMap([
  'FOO' => 'foo',
  'BAR' => 'bar',
]);

$mapper->input(['FOO' => 'foo value', 'BAR' => 'bar value']);
// ['foo' => 'foo value', 'bar' => 'bar value']

$mapper->output(['foo' => 'foo value', 'bar' => 'bar value']);
// ['FOO' => 'foo value', 'BAR' => 'bar value']
```

### ArrayValues

Применяет преобразования к указанным значениям ассоциативного массива.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\ArrayValues([
  'active' => new Mapping\BooleanType('yes', 'no'),
]);

$mapper->input(['active' => 'yes']); // ['active' => true]
$mapper->output(['active' => true]); // ['active' => 'yes']
```

### AsIs

Оставляет значения как они есть.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\AsIs();
$mapper->input('foo'); // 'foo'
$mapper->output('foo'); // 'foo'
```

### BooleanType

Преобразовывает значение в булев тип.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\BooleanType();
$mapper->input('true'); // true
$mapper->output(true); // 'true'

$mapper = new Mapping\BooleanType(true: 'да', false: 'нет');
$mapper->input('Нет'); // false
$mapper->output(false); // 'нет'
```

### Callback

Позволяет использовать для преобразования функции обратного вызова.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\Callback(
  input: strtolower(...),
  output: strtoupper(...),
);

$mapper->input('FOO'); // 'foo'
$mapper->output('foo'); // 'FOO'
```

### Chained

Создаёт цепочку преобразований, выполняемых последовательно: в `input` от первого к последнему,
в `output` — в обратном порядке.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\Chained(
  $mapper1,
  $mapper2,
  // …
);
```

### Constant

Возвращает константное значение.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\Constant(input: 'foo', output: 'bar');
$mapper->input(uniqid()); // 'foo'
$mapper->output(uniqid()); // 'bar'
```

### EnumType

Преобразовывает значения перечисляемых типов.

```injectablephp
use App\SomeEnum;
use DobroSite\Mapping;

$mapper = new Mapping\EnumType(SomeEnum::class);
$mapper->input('foo'); // SomeEnum::Foo
$mapper->output(SomeEnum::Foo); // 'foo' 
```

### FloatType

Преобразовывает значение в вещественное число.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\FloatType();
$mapper->input('1234.56'); // 1_234.56

$mapper = new Mapping\FloatType(
  new \NumberFormatter('ru_RU', \NumberFormatter::DEFAULT_STYLE)
);
$mapper->input('1 234,56'); // 1_234.56
```

### Map

Преобразовывает значение на основе карты (ассоциативного массива).

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\Map(['foo' => 'bar']);
$mapper->input('foo'); // 'bar'
$mapper->output('bar'); // 'foo'
```

### Nullable

Модификатор для других преобразователей, разрешающий им принимать значение `null`.

```injectablephp
use DobroSite\Mapping;

$float = new Mapping\FloatType();
$nullable = new Mapping\Nullable($float);

$nullable->input('123'); // 123
$nullable->input(null); // NULL
$float->input(null); // → InvalidArgumentException
```

### ObjectConstructor

Отображает массив на объект, используя для создания объекта конструктор его класса.

_Подробнее см. «Работа с объектами» ниже._

В качестве аргумента `$class` в конструктор `ObjectConstructor` следует передать экземпляр `Mapper`,
который вернёт имя класса создаваемого объекта. 


```injectablephp
use App\Foo;
use DobroSite\Mapping;

$mapper = new Mapping\ObjectConstructor(Mapping\Constant(Foo::class));
$instanceOfFoo = $mapper->input(['foo' => 'foo value']);
```

```injectablephp
use App\Foo;
use App\Bar;
use DobroSite\Mapping;

$mapper = new Mapping\ObjectConstructor(
  Mapping\Callback(
    fn(array $properties) => array_key_exists('bar', $properties) ? Bar::class : Foo::class, 
  )
);

$instanceOfFoo = $mapper->input(['foo' => 'foo value']);
$instanceOfBar = $mapper->input(['bar' => 'bar value']);
```

### ObjectFactory

Отображает массив на объект, используя для создания объекта фабрику.

_Подробнее см. «Работа с объектами» ниже._

В качестве аргумента `$factory` в конструктор `ObjectFactory` следует передать фабрику для создания
нужных объектов.

```injectablephp
use DobroSite\Mapping;

$mapper = new Mapping\ObjectFactory('\App\factory_function');
$mapper = new Mapping\ObjectFactory(factory_function(...));
$mapper = new Mapping\ObjectFactory([Factory::class, 'staticMethod']);
$mapper = new Mapping\ObjectFactory([$factory, 'method']);
$mapper = new Mapping\ClassType\CallableObjectFactory(
  fn(string $foo, string $bar) => new SomeClass($foo, $bar)
);
```

## Работа с объектами

`TODO`
