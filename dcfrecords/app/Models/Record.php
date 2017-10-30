<?php 
namespace App\Models;

class Record extends Model {
	public $timestamps = true;
	public $primaryKey = 'id_record';

	public $fillable = ['name', 'lead', 'status'];
	protected $table = 'record';

	//relations
	public function contacts(){ return $this->hasMany('App\Models\Record\Contact', 'id_record', 'id_record'); }
	public function events(){ return $this->hasMany('App\Models\Record\Contact\Event', 'id_record', 'id_record'); }

	//custom functions
	public function buildRelations(){
		$this->contacts = $this->contacts()
			->where(['deleted' => 0])
			->orderBy('updated_at', 'desc')
			->orderBy('id_contact', 'desc')
			->get();

		if(!$this->contacts->isEmpty()){
			foreach($this->contacts as $contact){
				$contact->events = $contact->events()
					->orderBy('updated_at', 'desc')
					->orderBy('id_event', 'desc')
					->get();
				$event = $contact->events->first();
				$contact->last_event = !empty($event) ? $event->value : "";
				$contact->last_event_date = !empty($event) ? $event->updated_at : '';
			}
		}

		$this->events = $this->events()
			->orderBy('updated_at', 'desc')
			->orderBy('id_event', 'desc')
			->get();
		if(!$this->events->isEmpty()){
			foreach($this->events as $event){
				$event->contact = $event->contact;
			}
		}
	}
}