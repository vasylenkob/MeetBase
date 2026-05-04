<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Адмін
        $admin = User::create([
            'name'     => 'Адміністратор',
            'email'    => 'admin@meetbase.ua',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Організатор
        $organizer = User::create([
            'name'     => 'Іван Організатор',
            'email'    => 'organizer@meetbase.ua',
            'password' => bcrypt('password'),
            'role'     => 'organizer',
        ]);

        // Відвідувач
        $attendee = User::create([
            'name'     => 'Марія Відвідувач',
            'email'    => 'attendee@meetbase.ua',
            'password' => bcrypt('password'),
            'role'     => 'attendee',
        ]);

        // Категорії
        $categories = [
            ['name' => 'Концерти', 'slug' => 'concerts', 'icon' => '🎵'],
            ['name' => 'Конференції', 'slug' => 'conferences', 'icon' => '🎤'],
            ['name' => 'Фестивалі', 'slug' => 'festivals', 'icon' => '🎪'],
            ['name' => 'Спорт', 'slug' => 'sport', 'icon' => '⚽'],
            ['name' => 'Виставки', 'slug' => 'exhibitions', 'icon' => '🖼️'],
            ['name' => 'Майстер-класи', 'slug' => 'workshops', 'icon' => '🎨'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Заходи
        $events = [
            [
                'title'       => 'Рок-концерт "Океан Ельзи"',
                'category'    => 'concerts',
                'description' => "Грандіозний концерт легендарного гурту «Океан Ельзи» у Києві!\n\nПрограма включає хіти різних років, нові пісні та сюрпризи для глядачів. Не пропустіть незабутній вечір живої музики.",
                'starts_at'   => now()->addDays(10)->setTime(19, 0),
                'ends_at'     => now()->addDays(10)->setTime(22, 0),
                'location'    => 'Палац Спорту',
                'address'     => 'пл. Спортивна, 1, Київ',
                'latitude'    => 50.4316,
                'longitude'   => 30.5238,
                'price'       => 850,
                'capacity'    => 5000,
                'status'      => 'published',
            ],
            [
                'title'       => 'IT-конференція WebDay 2025',
                'category'    => 'conferences',
                'description' => "WebDay — найбільша веб-конференція України.\n\nСпікери з провідних компаній розкажуть про сучасні технології, тренди та практики розробки. Нетворкінг, воркшопи та розіграш призів.",
                'starts_at'   => now()->addDays(15)->setTime(9, 0),
                'ends_at'     => now()->addDays(15)->setTime(18, 0),
                'location'    => 'UNIT.City',
                'address'     => 'вул. Дорогожицька, 3, Київ',
                'latitude'    => 50.4700,
                'longitude'   => 30.4588,
                'price'       => 500,
                'capacity'    => 300,
                'status'      => 'published',
            ],
            [
                'title'       => 'Фестиваль вуличної їжі Street Food Fest',
                'category'    => 'festivals',
                'description' => "Дводенний фестиваль вуличної їжі на Контрактовій площі!\n\nПонад 50 учасників: фудтраки, ресторани, пекарні. Жива музика, розваги для дітей та дорослих.",
                'starts_at'   => now()->addDays(5)->setTime(11, 0),
                'ends_at'     => now()->addDays(6)->setTime(22, 0),
                'location'    => 'Контрактова площа',
                'address'     => 'Контрактова пл., Київ',
                'latitude'    => 50.4634,
                'longitude'   => 30.5164,
                'price'       => 0,
                'capacity'    => 2000,
                'status'      => 'published',
            ],
            [
                'title'       => 'Майстер-клас з акварельного живопису',
                'category'    => 'workshops',
                'description' => "Навчіться малювати акварель з нуля за 3 години!\n\nДосвідчений художник поступово проведе вас від базових технік до завершеної роботи. Всі матеріали включені.",
                'starts_at'   => now()->addDays(7)->setTime(14, 0),
                'ends_at'     => now()->addDays(7)->setTime(17, 0),
                'location'    => 'Art Studio Kyiv',
                'address'     => 'вул. Хрещатик, 10, Київ',
                'latitude'    => 50.4501,
                'longitude'   => 30.5234,
                'price'       => 350,
                'capacity'    => 15,
                'status'      => 'published',
            ],
            [
                'title'       => 'Kyiv Marathon 2025',
                'category'    => 'sport',
                'description' => "Щорічний Київський марафон — одна з найбільших спортивних подій України.\n\nДистанції: 42 км, 21 км, 10 км, 5 км. Учасники з понад 50 країн світу.",
                'starts_at'   => now()->addDays(20)->setTime(8, 0),
                'ends_at'     => now()->addDays(20)->setTime(14, 0),
                'location'    => 'Хрещатик',
                'address'     => 'вул. Хрещатик, Київ',
                'latitude'    => 50.4470,
                'longitude'   => 30.5218,
                'price'       => 0,
                'capacity'    => 10000,
                'status'      => 'published',
            ],
            [
                'title'       => 'Виставка сучасного мистецтва "Форми"',
                'category'    => 'exhibitions',
                'description' => "Групова виставка молодих українських художників.\n\nЖивопис, скульптура, інсталяції — 30 авторів досліджують тему форми та простору.",
                'starts_at'   => now()->addDays(3)->setTime(10, 0),
                'ends_at'     => now()->addDays(17)->setTime(20, 0),
                'location'    => 'Мистецький арсенал',
                'address'     => 'вул. Лаврська, 10-12, Київ',
                'latitude'    => 50.4354,
                'longitude'   => 30.5545,
                'price'       => 100,
                'capacity'    => 500,
                'status'      => 'published',
            ],
            [
                'title'       => 'Jazz у парку — літній сезон',
                'category'    => 'concerts',
                'description' => "Серія безкоштовних джазових вечорів просто неба.\n\nМолоді українські джаз-музиканти виступлять щовихідних у парку Шевченка. Беріть пледи та друзів!",
                'starts_at'   => now()->addDays(12)->setTime(18, 0),
                'ends_at'     => now()->addDays(12)->setTime(21, 0),
                'location'    => 'Парк Шевченка',
                'address'     => 'бул. Тараса Шевченка, Київ',
                'latitude'    => 50.4436,
                'longitude'   => 30.5076,
                'price'       => 0,
                'capacity'    => 300,
                'status'      => 'pending',
            ],
            [
                'title'       => 'UX Design Meetup #12',
                'category'    => 'conferences',
                'description' => "Щомісячна зустріч UX-дизайнерів Києва.\n\nОбговорення кейсів, нетворкінг, короткі доповіді від учасників.",
                'starts_at'   => now()->subDays(10)->setTime(18, 30),
                'ends_at'     => now()->subDays(10)->setTime(21, 0),
                'location'    => 'Creative Quarter',
                'address'     => 'вул. Велика Васильківська, 72, Київ',
                'latitude'    => 50.4331,
                'longitude'   => 30.5202,
                'price'       => 0,
                'capacity'    => 60,
                'status'      => 'published',
            ],
            [
                'title'       => 'Зимовий фотомарафон',
                'category'    => 'workshops',
                'description' => "Одноденний фотомарафон вулицями зимового Києва.\n\nУчасники отримують завдання та 6 годин, щоб втілити їх у серії світлин. Підсумкова виставка — в кафе-галереї.",
                'starts_at'   => now()->subDays(5)->setTime(10, 0),
                'ends_at'     => now()->subDays(5)->setTime(18, 0),
                'location'    => 'Арт-кафе «Метаморфози»',
                'address'     => 'пров. Музейний, 4, Київ',
                'latitude'    => 50.4488,
                'longitude'   => 30.5217,
                'price'       => 200,
                'capacity'    => 25,
                'status'      => 'published',
            ],
        ];

        foreach ($events as $eventData) {
            $catSlug = $eventData['category'];
            unset($eventData['category']);

            $category = Category::where('slug', $catSlug)->first();
            $title = $eventData['title'];

            Event::create(array_merge($eventData, [
                'user_id'     => $organizer->id,
                'category_id' => $category->id,
                'slug'        => Str::slug($title) . '-' . Str::random(6),
            ]));
        }

        // Демо реєстрації відвідувача
        $upcomingEvent = Event::where('status', 'published')->where('starts_at', '>', now())->first();
        Registration::create([
            'event_id'       => $upcomingEvent->id,
            'user_id'        => $attendee->id,
            'ticket_code'    => strtoupper(Str::random(10)),
            'payment_status' => 'paid',
            'status'         => 'active',
        ]);

        // Реєстрації на минулі заходи
        $pastEvents = Event::where('ends_at', '<', now())->get();
        foreach ($pastEvents as $pastEvent) {
            Registration::create([
                'event_id'       => $pastEvent->id,
                'user_id'        => $attendee->id,
                'ticket_code'    => strtoupper(Str::random(10)),
                'payment_status' => $pastEvent->isFree() ? 'free' : 'paid',
                'status'         => 'active',
            ]);
        }

        // Демо коментарі (100 шт. для тестування пагінації)
        $demoEvent = Event::where('status', 'published')->where('starts_at', '>', now())->first();

        $fakeBodies = [
            'Чудовий захід, дуже чекаю! Вже купила квиток 🎉',
            'Дякуємо за підтримку! Обіцяємо незабутній вечір.',
            'Хто їде разом? Шукаю компанію!',
            'Був на минулорічному — просто вогонь, цього разу теж буду!',
            'Чи буде трансляція онлайн?',
            'Скільки часу займає дорога від метро?',
            'Чи є знижки для студентів?',
            'Беру квитки на всю сімʼю, дякую за організацію!',
            'Надіюсь, буде паркування поруч.',
            'Вже 3-й рік відвідую цей захід — завжди на висоті!',
            'Чи можна прийти з дітьми?',
            'Дякую організаторам за чудову роботу!',
            'Квитки ще є у продажу?',
            'Буде ще один день? Хочу прийти обидва дні.',
            'Поділіться програмою заходу, будь ласка.',
            'Перший раз йду на такий формат — чого очікувати?',
            'Це буде мій день народження — чудовий подарунок собі!',
            'Чи буде фотозона?',
            'Дуже чекаю нетворкінгу після основної програми.',
            'Сподіваюсь на гарну погоду для відкритого майданчика!',
        ];

        $users = [$attendee, $organizer, $admin];

        for ($i = 1; $i <= 97; $i++) {
            Comment::create([
                'event_id'   => $demoEvent->id,
                'user_id'    => $users[$i % 3]->id,
                'body'       => $fakeBodies[$i % count($fakeBodies)],
                'status'     => 'approved',
                'created_at' => now()->subMinutes(97 - $i),
                'updated_at' => now()->subMinutes(97 - $i),
            ]);
        }

        // Останні 3: 2 approved + 1 pending (чітко помітні у кінці)
        Comment::create(['event_id' => $demoEvent->id, 'user_id' => $attendee->id,  'body' => 'Чудовий захід, дуже чекаю! Вже купила квиток 🎉',  'status' => 'approved']);
        Comment::create(['event_id' => $demoEvent->id, 'user_id' => $organizer->id, 'body' => 'Дякуємо за підтримку! Обіцяємо незабутній вечір.',  'status' => 'approved']);
        Comment::create(['event_id' => $demoEvent->id, 'user_id' => $attendee->id,  'body' => 'Чи буде можливість придбати квитки на вході?',       'status' => 'pending']);
    }
}
