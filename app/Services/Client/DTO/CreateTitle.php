<?php

namespace App\Services\Client\DTO;

class CreateTitle
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createTitle()
    {
        $cartItemsDescription = '';

        if (isset($this->data['cart_items']) && is_array($this->data['cart_items'])) {
            foreach ($this->data['cart_items'] as $item) {
                $cartItemsDescription .= 'Продукт: #' . $item['product_id'] . ', Количество: #' . $item['amount'] . '; ';
            }
        }

        return [
            'title' => 'Сборка заказа #' . $this->data['order_id'],
            'columnId' => '4ca91005-e436-4d0a-b47f-f01c1b8d0d77',  // ID колонки
            'description' =>
                'номер заказа #' . $this->data['order_id'] . '<br>' .
                'номер пользователя #' . $this->data['user_id'] . '<br>' .
                'адрес пользователя #' . $this->data['address'] . '<br>' .
                'телефон пользователя #' . $this->data['phone'] . '<br>' .
                'продукты пользователя: ' . $cartItemsDescription
        ];
    }


}
