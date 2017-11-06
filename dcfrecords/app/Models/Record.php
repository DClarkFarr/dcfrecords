<?php 
namespace App\Models;

class Record extends Model {
	public $timestamps = true;
	public $primaryKey = 'id_record';

	public $fillable = ['name', 'lead', 'status'];
	protected $table = 'record';

	protected $hidden = ['id_user'];

	//relations
	public function contacts(){ return $this->hasMany('App\Models\Record\Contact', 'id_record', 'id_record'); }
	public function events(){ return $this->hasMany('App\Models\Record\Contact\Event', 'id_record', 'id_record'); }
	public function user(){ return $this->hasOne('App\Models\User', 'id_user', 'id_user'); }

	//custom functions
	public function buildRelations(){
		$this->contacts = $this->contacts()
			->where(['deleted' => 0])
			->orderBy('updated_at', 'desc')
			->orderBy('id_contact', 'desc')
			->get();

		if(!$this->contacts->isEmpty()){
			foreach($this->contacts as $contact){
				$contact->buildRelations();
			}
		}

		$this->events = $this->events()
			->orderBy('updated_at', 'desc')
			->orderBy('id_event', 'desc')
			->get();

		if(!$this->events->isEmpty()){
			foreach($this->events as $event){
				$event->buildRelations();
			}
		}

		$this->user = $this->user;
	}
}