<?php

use App\Menu;
use App\Meal;

class MenuFactory
{
    public static function createWithMeals($overrides, $date, $mealQuantity = 1)
    {
        $menu = factory(Menu::class)->create($overrides);

        foreach (range(1, $mealQuantity) as $i) {
            factory(Meal::class)->create(['menu_id' => $menu->id, 'date' => $date, 'price' => $menu->price]);
        }

        return $menu;
    }
}
