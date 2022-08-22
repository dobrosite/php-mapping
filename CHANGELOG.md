# История изменений

Формат этого файла соответствует рекомендациям
[Keep a Changelog](https://keepachangelog.com/ru/1.0.0/). Проект использует
[семантическое версионирование](http://semver.org/spec/v2.0.0.html).

## Не выпущено

### Изменено

- `Properties` теперь поддерживает интерфейс `Iterator`.


## 0.2.0 - 19.08.2022

### Добавлено

- Новые типы:
  - `CustomType` 
  - `FloatType`
  - `MapType`
  - `NullableType`


## 0.1.0 - 19.08.2022

### Добавлено

- Типы:
  - `Type`
  - `ClassType`
    - `TargetClassResolver`
      - `ClassName`
    - `Properties`
      - `Property`
    - `ObjectFactory`
      - `AbstractObjectFactory`
      - `DefaultObjectFactory`
      - `CallableObjectFactory`
  - `EnumType`
  - `SameType`
- Исключения:
  - `ConfigurationError`
  - `DataError`
- Прочее:
  - `DefaultValue`
