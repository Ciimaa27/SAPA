<?php

namespace App\Models;
use App\Models\Device;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LogTap extends Model
{
    protected $table = 'log_tap';
    protected $primaryKey = 'id';
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

    /**
     * Accessor untuk mendapatkan jenis perangkat
     * Deteksi otomatis berdasarkan uid_rfid atau fingerprint_id
     */
    protected function jenisPerangkat(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getDeviceType()
        );
    }

    /**
     * Helper method untuk deteksi jenis perangkat
     */
    public function getDeviceType()
    {
        if ($this->device && $this->device->nama_device) {
            return $this->device->nama_device;
        }

        if ($this->uid_rfid) {
            return 'RFID';
        }

        if ($this->fingerprint_id) {
            return 'Fingerprint';
        }

        return '-';
    }
}