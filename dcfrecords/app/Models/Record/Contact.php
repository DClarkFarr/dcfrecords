<?php 
namespace App\Models\Record;

use App\Models\Model;

class Contact extends Model {
	public $timestamps = true;
	protected $primaryKey = 'id_contact';
	protected $table = 'record_contact';

	public $fillable = ['id_record', 'type', 'label', 'value', 'status', 'deleted'];

	public function record(){ return $this->belongsTo('App\Models\Record', 'id_record', 'id_record'); }
	public function events(){ return $this->hasMany('App\Models\Record\Contact\Event', 'id_contact', 'id_contact'); }
}