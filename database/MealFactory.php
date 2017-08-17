<?php

use App\Meal;

class MealFactory
{
    public static function createWithDates($data)
    {
        foreach ($data as $date => $menus) {
            foreach ($menus as $menu) {
                factory(Meal::class)->create(['menu_id' => $menu->id, 'date' => $date]);
            }
        }
    }
}
