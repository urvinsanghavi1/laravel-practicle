<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\MainTableConstans as mainTableConstans;

class Tenant extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = mainTableConstans::TENANT_TABLE_NAME;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        mainTableConstans::TENANT_TABLE_HOSTNAME,
        mainTableConstans::TENANT_TABLE_PORT,
        mainTableConstans::TENANT_TABLE_DBNAME,
        mainTableConstans::TENANT_TABLE_DBUSERNAME,
        mainTableConstans::TENANT_TABLE_COMPANY_ID,
        mainTableConstans::TENANT_TABLE_DBPASSWORD,
        mainTableConstans::TENANT_TABLE_DOMAIN_NAME
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        mainTableConstans::TENANT_TABLE_DBPASSWORD
    ];
}
