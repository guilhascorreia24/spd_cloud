<?php
namespace App;
use Illuminate\Support\Facades\DB;
class Shelter_model
{
    public static function my_pets($user_id)
    {
        $sql = "Select
        pets.id,
        pets.name,
        pets.description,
        pets.image,
        adoptions.created_at
    From
        adoptions Inner Join
        pets On adoptions.pet_id = pets.id 
    Where
        adoptions.petlover_id = $user_id";
        return DB::select($sql);
    } 
}