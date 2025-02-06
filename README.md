# Описание
 
[компонент для работы geo ip публичных серверов](local/components/night-pilgrim)
     
для обращения к апи сервиса создан класс-провайдер [ipstackprovider](local/php_interface/lib/localclasses/lib/ipstackprovider.php)
     
он реализует интерфейс [geoipinterface](local/php_interface/lib/localclasses/interfaces/geoipinterface.php) 

для записи в hl создзана орм [geoipinfolocaltable](local/php_interface/lib/localclasses/orm/geoipinfolocaltable.php)