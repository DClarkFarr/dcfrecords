<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record\Contact\Event;

class EventsController extends Controller {

	public function deleteEventAction(){
		$id_event = \Request::get('id_event');
		$id_record = \Request::get('id_record');

		$event = Event::where([
			'id_event' => $id_event,
			'id_record' => $id_record,
		])->first();

		if(empty($event)){
			return ['status' => 'failed', 'message' => 'Event Not Found'];
		}

		$event->delete();

		return ['status' => 'success'];
	}
}