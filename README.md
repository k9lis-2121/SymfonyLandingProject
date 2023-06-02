# SymfonyLandingProject
Проект с фриланс заказа, практическая часть для дипломной работы.

По заданию требовалось кратко пояснить этапы разработки.
1. Создаем docker-compose.yml в котором описываем нужную среду для разработки.
2. Для сборки php так же необходим файл Dockerfile. В нем описываются параметры сборки php. Остальные компоненты беруться из готовых образов на docker hub.
3. Для конфигурирования php создаем одноименную директорию в корне проекта с файлом local.ini
4. Через консоль разварачиваем сам symfony 6 следующей командой: "composer create-project symfony/website-skeleton:"^6.0" symfony" в результате этой команды 
будет создана директория symfony, которая будет примонтирована в среду docker.
5. Собираем докер контейнер командой: "docker-compose up --build", после этого наш проект будет доступен в браузере по адресу http://localhost/

6. Создаем Entity (Сущность) posts для постов на главной страницы командой: "php bin/console make:entity Post".
id - int, primary key, autoincriment (Создаеться автоматически);
title - strig, lenght 255;
post - text.
Все поля обязательны для заполнения.
7. Создаем Entity для заявок statement командой: "php bin/console make:entity Statement" (такое название выбрано что бы избежать проблем с возможными зарезервированными словами 
любого из используемых инструментов, в ином случае корректнее было бы назвать сущсность request):
full_name - strig, lenght 255;
furniture - strig, lenght 255;
phone - strig, lenght 255;

Все поля string для возможности не ограничиваться типом данных при разработке, т.к. на практике, может измениться бизнес-логкиа и тип данных может быть любым, 
с необходимостью сохранить имеющиеся данные. Всегда можно изменить сущность, но такой подход позволит упростить внесение необходимых изменений.

8. Создаем миграцию командой: "php bin/console make:migration".
9. Выполняем миграцию командой: "php bin/console doctrine:migrations:migrate".

10. Устанавливаем через композер библиотеку для работы с аннотациями, для упрощенной генерации CRUD (create, reed, update, delete) контроллера командой: "composer require annotations".

11. Создаем CRUD контроллер для постов командой: "php bin/console make:crud Post". После этой команды у нас создан контроллер, и шаблоны для него, что уже позволяет нам просматривать, 
создавать, редактировать и удалять посты.
12. Создаем CRUD контроллер для заявок командой: "php bin/console make:crud Statement". После этой команды у нас создан контроллер, и шаблоны для него, что уже позволяет нам просматривать, 
создавать, редактировать и удалять посты.

13. Так как у нас уже есть некоторые страницы, для их минимального оформления на текущем этапе, подключим с помощью CDN Bootstrap. Т.к. это легкий CSS-fraemwork, 
нет никакой необходимости в его установке локально. Подключив его с поощью CDN в общий для всех шаблонов файл base.html.twig мы применим его ко всем шаблонам,
не засоряя проект файлами стилей.

14. Дополним Entity Post, добавив в нее метод getAll внутри которого через репозиторий мы запрашиваем все записи постов из базы.
15. Созданые нами CRUD подходят для админ панели, но не для целевого пользователя, поэтому создаем контроллер home используя комманду: "php bin/console make:controller Home".
15.1 Подключаем entity post с помощбю use,
15.2 Роут в нем меняем:
#[Route('/home', name: 'app_home')]
На:
#[Route('/', name: 'app_home')]
Так как это будет наша домашняя страница,
15.3 Создаем экземпляр класса сущности Post и вызываем у нее созданный нами метод getAll.
15.4 Возвращаем массив шаблону home.
15.5 в шаблоне в цикле перебираем массив и выводим посты.
15.6 добавляем в шаблон кнопку ведущую на составление заявки

16. Теперь создадим авторизацию для админа. Для базовой авторизации в симфони есть консольная комманда make:user,
она создаст необходимую сущность (Entity), на все хапросы команды мы ответили утвердительно кроме поля по которому будет
проходить авторизация. По умолчанию email, мы выбрали username.
17. Снова создаем и выполняем миграцию.
18. Далее создаем необходимые классы и шаблон формы для авторизации, с помощью php bin/console make:auth
19. После этой комманды нужно внести правку в созданный файл Security/AuthAuthenticator.php
Нужно в методе onAuthenticationSuccess() заменить исключение, на переадресацию на домашнюю страницу указав не путь, а имя app_home.
Если этого не сделать, то если по какой-либо причине не будет передано сохраненного url для перенаправления, мы получим ошибку.
20. Класс с шаблоном для регистрации мы создадим так же одной коммандой php bin/console make:registration
Теперь мы можем зарегестрировать нашего администратора перейдя http://localhost/register
Теперь у нас есть пути:
/register
/login
/logout
21. Настраиваем доступы. Т.к. доступа к созданию чего либо кроме заявки у посетителей сайта не должно быть.
В файле config/packages/security.yaml находим раздел access_control и указываем к каким ресурсам есть публичный доступ, а какой только у авторизированных пользователей.
Тут важно отметить, что у пользователей есть роли, и по умолчанию при регистрации устанавливается USER_ROLE, но менять ее в данном случаее
мы не будем, т.к. пользователь у нас только один.
22. Создадим панель навигации администратора, которая будет отображаться только администратору.
Для этого опишим ее стили в шаблоне templates/base.html.twig внутри проверки роли пользователя
{% if is_granted('ROLE_USER') %}
    // панель
{% endif %}
Так же внутри панели мы укажем относительный пути при помощи path. Мы указываем относительные пути, т.к. домен и ip адрес могут измениться в разных ситуациях.