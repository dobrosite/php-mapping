# История изменений

Формат этого файла соответствует рекомендациям
[Keep a Changelog](https://keepachangelog.com/ru/1.0.0/). Проект использует
[семантическое версионирование](http://semver.org/spec/v2.0.0.html).

## Не выпущено

### Добавлено

- Новые типы:
  - `FloatType`


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
