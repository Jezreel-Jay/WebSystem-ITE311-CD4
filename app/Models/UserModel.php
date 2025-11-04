<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
       
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'is_deleted',
        'created_at',
        'updated_at',

        
    ];


    // // Automatically mark as deleted using is_deleted column
    protected $useSoftDeletes = false;
    protected $deletedField   = 'is_deleted';

    //   Optional (helps with automatic date handling)
    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

    protected $useTimestamps = false; // timestamps handled by DB defaults in migration
}


