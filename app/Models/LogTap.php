<?php

namespace App\Models;
use App\Models\Device;

use Illuminate\Database\Eloquent\Model;

class LogTap extends Model
{
    protected $table = 'log_tap';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_device',
        'uid_rfid',
        'fingerprint_id',
        'keterangan',
        'created_at'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'id_device');
    }
}