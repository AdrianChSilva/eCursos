<?php

namespace App\VueTables;

interface VueTablesInterface
{
    public function get($model, Array $fields, Array $relations = []);
}
