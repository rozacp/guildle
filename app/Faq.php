<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

	protected $fillable = ['title', 'body'];

	protected $table = 'faqs';

}
