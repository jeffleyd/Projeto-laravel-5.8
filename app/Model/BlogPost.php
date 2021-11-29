<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_post';
	
	public function blog_post_attach() {
		return $this->hasMany(BlogPostAttach::class, 'blog_post_id', 'id');
	}
}
