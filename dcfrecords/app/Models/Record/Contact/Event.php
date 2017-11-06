<?php 
namespace App\Models\Record\Contact;

use App\Models\Model;

class Event extends Model {
	public $timestamps = true;
	protected $primaryKey = 'id_event';
	protected $table = 'record_contact_event';

	public $fillable = ['id_record', 'id_contact', 'id_user', 'type', 'value'];

	//relationshipts
	public function record(){ return $this->belongsTo('App\Models\Record', 'id_record', 'id_record'); }
	public function contact(){ return $this->belongsTo('App\Models\Record\Contact', 'id_contact', 'id_contact'); }
	public function user(){ return $this->belongsTo('App\Models\User', 'id_user', 'id_user'); }

	public static function contactStatus($contact){
		return self::event($contact, ['type' => 'contact.status', 'value' => $contact->status]);
	}

	public static function event($contact, $args = []){

		$params = array_intersect_key($contact->toArray(), array_flip($contact->fillable));
		$params = array_merge($params, $args);
		$params['id_contact'] = $contact->id_contact;
		$params['id_user'] = \Config::get('user.id_user');
		
		$event = new self;
		$event->fill($params);
		$event->save();

		return $event;
	}

	public function buildRelations(){
		$this->contact;
		$this->user;
	}
}