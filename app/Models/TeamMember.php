<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasSlugAndMedia;

    protected $table = 'team_members';

    protected $fillable = ['name', 'role', 'bio', 'photo', 'linkedin', 'email', 'sort', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean'
        ];
    }
}
