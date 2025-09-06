<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Иван Петров',
                'email' => 'ivan.petrov@example.com',
                'phone' => '+7 (999) 123-45-67',
                'company' => 'ООО "ТехноСтрой"',
                'tax_number' => '1234567890',
                'status' => 'active',
                'address' => 'г. Москва, ул. Ленина, д. 1, оф. 101',
                'notes' => 'Крупный клиент, предпочитает премиум продукты',
            ],
            [
                'name' => 'Мария Сидорова',
                'email' => 'maria.sidorova@example.com',
                'phone' => '+7 (999) 234-56-78',
                'company' => 'ИП Сидорова М.А.',
                'tax_number' => '0987654321',
                'status' => 'active',
                'address' => 'г. Санкт-Петербург, пр. Невский, д. 50',
                'notes' => 'Частный клиент, заказывает регулярно',
            ],
            [
                'name' => 'Алексей Козлов',
                'email' => 'alexey.kozlov@example.com',
                'phone' => '+7 (999) 345-67-89',
                'company' => 'ООО "СтройМаркет"',
                'tax_number' => '1122334455',
                'status' => 'prospect',
                'address' => 'г. Екатеринбург, ул. Мира, д. 15',
                'notes' => 'Потенциальный клиент, интересуется оптовыми заказами',
            ],
            [
                'name' => 'Елена Волкова',
                'email' => 'elena.volkova@example.com',
                'phone' => '+7 (999) 456-78-90',
                'company' => 'ООО "ДизайнСтудия"',
                'tax_number' => '5544332211',
                'status' => 'active',
                'address' => 'г. Новосибирск, ул. Красная, д. 25',
                'notes' => 'Дизайнер, заказывает материалы для проектов',
            ],
            [
                'name' => 'Дмитрий Соколов',
                'email' => 'dmitry.sokolov@example.com',
                'phone' => '+7 (999) 567-89-01',
                'company' => 'ООО "РемонтСервис"',
                'tax_number' => '6677889900',
                'status' => 'inactive',
                'address' => 'г. Казань, ул. Баумана, д. 10',
                'notes' => 'Бывший клиент, не заказывает с прошлого года',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}

