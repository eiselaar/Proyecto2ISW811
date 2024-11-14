<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostSocialAccount extends Pivot
{
    protected $table = 'post_social_accounts';

    protected $fillable = [
        'post_id',
        'social_account_id',
        'post_id_on_social',
    ];
}