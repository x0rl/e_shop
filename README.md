# about
Сайт разрабатывается по сей день, сделать еще нужно много чего и код будет переписываться не раз, но вот что имеется на данный момент

ТЗ
Реализуйте интернет магазин. В нем должны быть товары, категории, подкатегории. Список категорий и подкатегорий должен размещаться в сайдбаре сайта. У каждого товара должна быть цена, картинка (обязательно будет в будущем!), кнопка 'в корзину'. Реализуйте регистрацию пользователей. Зарегистрированный пользователь имеет личный кабинет, в нем он видит свою корзину, а также список своих покупок. Реализуйте админку, в которой можно добавлять, удалять и редактировать товары. Также в админке виден список пользователей. Админ может забанить и разбанить пользователя, а также повысить его до админа. Также в админке должна быть статистика покупок - сумма продаж по месяцам.
Что я добавил от себя
Товар можно купить, у каждого пользователя есть баланс. После покупки можно оставить отзыв и выставить оценку, в личном кабинете есть список всех оставленных отзывов. Зарегистрированные пользователи могут оставлять комментарии к товару. В каталоге можно отсортировать товары (цена возр. и убыв., рейтинг, количество отзывов, новизна). Пагинация применяется везде, где необходима.
По коду
Использовал php 8, laravel 8, bootstrap 4. Регистрация и авторизация из коробки, обращения к базе данных через eloquent.
