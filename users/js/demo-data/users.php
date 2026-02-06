<?php
// Демо-дані користувачів
$demoUsers = [
    ['id' => 1, 'surname' => 'Іваненко', 'name' => 'Іван', 'patronymic' => 'Іванович', 'email' => 'ivan@example.com', 'role' => 'user'],
    ['id' => 2, 'surname' => 'Петренко', 'name' => 'Петро', 'patronymic' => 'Петрович', 'email' => 'petro@example.com', 'role' => 'worker'],
    ['id' => 3, 'surname' => 'Ковальчук', 'name' => 'Евеліна', 'patronymic' => 'Богданівна', 'email' => 'evelina@example.com', 'role' => 'admin'],
    ['id' => 4, 'surname' => 'Коваленко', 'name' => 'Олена', 'patronymic' => 'Олексіївна', 'email' => 'olena@example.com', 'role' => 'user'],
];

// Демо-дані замовлень (зв'язані через user_id)
$demoAppointments = [
    [
        'id' => 1, 
        'user_id' => 1, 
        'date' => '2025-05-22', 
        'time' => '15:00:00', 
        'person_type' => 'individual', 
        'category' => 'Квартира', 
        'service' => 'Інформаційна довідка', 
        'status' => 'завершено',
        'document_ids' => [1, 2]
    ],
    [
        'id' => 2, 
        'user_id' => 2, 
        'date' => '2025-05-22', 
        'time' => '11:00:00', 
        'person_type' => 'individual', 
        'category' => 'Квартира', 
        'service' => 'Технічний паспорт', 
        'status' => 'завершено',
        'document_ids' => [1, 2]
    ],
    [
        'id' => 3, 
        'user_id' => 3, 
        'date' => '2025-05-22', 
        'time' => '15:00:00', 
        'person_type' => 'individual', 
        'category' => 'Будинок', 
        'service' => 'Інформаційна довідка', 
        'status' => 'в процесі',
        'document_ids' => [1, 2]
    ]
];

/**
 * Емуляція SQL JOIN та фільтрації
 */
class DemoDB {
    public static function getAppointmentsWithUsers() {
        global $demoUsers, $demoAppointments;
        $result = [];
        
        foreach ($demoAppointments as $app) {
            // Шукаємо користувача для кожного замовлення (аналог JOIN)
            $user = null;
            foreach ($demoUsers as $u) {
                if ($u['id'] === $app['user_id']) {
                    $user = $u;
                    break;
                }
            }
            
            $app['user_data'] = $user;
            $app['user_full_name'] = $user ? "{$user['surname']} {$user['name']} {$user['patronymic']}" : 'Невідомий';
            $result[] = $app;
        }
        return $result;
    }

    public static function filterBySurname($surname) {
        $all = self::getAppointmentsWithUsers();
        if (empty($surname)) return $all;

        return array_filter($all, function($app) use ($surname) {
            return mb_stripos($app['user_data']['surname'], $surname) !== false;
        });
    }

    public static function filterByStatus($status) {
        $all = self::getAppointmentsWithUsers();
        if (empty($status)) return $all;

        return array_filter($all, function($app) use ($status) {
            return $app['status'] === $status;
        });
    }
}