###  Замена битриксового редактора на CKEditor 5

Где: детальные страницы и подразделы (также описания инфоблоков)

### Сборка редактора

Перейти в src

Выполнить npm install

Отредактировать конфиг на свое усмотрение добавив плагинов или настроек

src/src/ckeditor.js

Собрать: yarn run build или npm npm run-script build	

Итоговый файл подключается бандлом автоматически.

### Нюансы

CKEditor 5 по принципиальным соображениям (авторы: "это редактор контента и все") не поддерживает
изменение Source code (HTML кода напрямую).

Бандл проверяет - подключен ли уже модуль 'prokl.ckeditor'. Если да, то во избежание дублирования
и конфликтов - закругляется. 

Если установлен модуль prokl.ckeditor - бандл не инициализируется во избежание конфликтов.

После инициализации бандла необходимые файлы JS/CSS будут однократно скопированы в папку `/local/assets/ck5editor-bundle/`.
Их нужно добавить в git и проверить, что эта папка доступна из корня сайта. 
