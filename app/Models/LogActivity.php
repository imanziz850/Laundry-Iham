<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Request;
class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'subject',
        'method',
        'url',
        'agent',
    ];

    public function scopeAdd($query, $subject)
    {
        $data = [];
        $data['user_id'] = Auth::id();
        $data['subject'] = $subject;
        $data['ip'] = Request::ip();
        $data['method'] = Request::method();
        $data['url'] = Request::fullUrl();
        $data['agent'] = Request::header('User-Agent');
        return $query->create($data);
    }
    public function scopeList($query, $nama, $role = null)
    {
        return $query->from('log_activities AS log')
            ->join('users AS u', 'u.id', 'log.user_id')
            ->when($nama, function ($q, $nama) {
                return $q->where('u.nama', 'like', "%{$nama}%")
                    ->orWhere('log.subject', 'like', "%{$nama}%");
            })
            ->when($role, function ($q, $role) {
                return $q->whereIn('u.role', $role);
            })
        ->orderBy('id', 'desc')
        ->select('log.id as id', 'nama', 'role', 'subject', 'ip', 'agent', 'log.created_at AS tanggal')
        ->paginate(50);
    }
}
