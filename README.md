# oat-tech-exercise
## Installing
1. Sure you have installed *docker* & *docker-compose* on your linux machine
2. Clone this repository and execute ``docker-compose up -d`` in the project root

HTTP server would be running on http://localhost:8080/ (change port in *docker-compose.yml* if needed)

## Configuring

### Storage format
Look at file ``config/services.yaml`` to change storage format:
```yaml
    # Chose data format by swapping DataFormatter implementation below
    App\Storage\Question\DataFormatterInterface:
        #class: App\Storage\Question\JsonDataFormatter
        class: App\Storage\Question\CsvDataFormatter
```

### Translation Adapter
Now it is only one adapter ``GoogleTranslatorAdapter`` which uses [stichoza/google-translate-php](https://github.com/Stichoza/google-translate-php) library but you can add your own implementation and load it by changing:
```yaml
    App\Service\Translator\AdapterInterface:
        class: App\Service\Translator\Adapter\GoogleTranslatorAdapter
```

## REST API
To explore API documentation you could copy [open-api.yaml](docs/open-api.yaml) content to https://editor.swagger.io/