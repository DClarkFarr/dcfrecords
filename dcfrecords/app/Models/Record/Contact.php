<?php 
namespace App\Models\Record;

use App\Models\Model;

class Contact extends Model {
	public $timestamps = true;
	protected $primaryKey = 'id_contact';
	protected $table = 'record_contact';

	protected $hidden = ['id_user'];

	public $fillable = ['id_record', 'type', 'label', 'value', 'status', 'deleted'];

	public function record(){ return $this->belongsTo('App\Models\Record', 'id_record', 'id_record'); }
	public function events(){ return $this->hasMany('App\Models\Record\Contact\Event', 'id_contact', 'id_contact'); }
	public function user(){ return $this->hasOne('App\Models\User', 'id_user', 'id_user'); }

	public function buildRelations(){
		$this->events = $this->events()
			->orderBy('updated_at', 'desc')
			->orderBy('id_event', 'desc')
			->get();

		if(!$this->events->isEmpty()){
			foreach($this->events as $event){
				$event->buildRelations();
			}
		}
		$event = $this->events->first();
		$this->last_event = !empty($event) ? $event->value : "";
		$this->last_event_date = !empty($event) ? $event->updated_at : '';
		$this->user;
	}
}