<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record\Contact;

class ContactsController extends Controller {
	public function getContactsAction(){
		$post = (object) \Request::all();

		$args = ['deleted' => 0];

		$rows = Contact::where($args)->orderBy('updated_at', 'desc')->orderBy('id_contact', 'desc')->get();
		return $rows;
		
	}

	public function getContactAction(){
		$id_contact = \Request::get('id_contact');
		$id_record = \Request::get('id_record');

		//$record = Record::find($id_record);
		$contact = Contact::where([
			'id_contact' => $id_contact,
			'id_record' => $id_record,
		])->first();
		
		$contact->record;

		if(!$contact){
			return ['status' => 'failed', 'message' => 'Could not find Record Contact'];
		}

		return ['status' => 'success', 'contact' => $contact];
	}

	public function saveContactAction(){
		$id_contact = \Request::get('id_contact');
		$id_record = \Request::get('id_record');
		$fields = \Request::get('fields');

		if($id_contact){
			$contact = Contact::find($id_contact);
		}

		if(empty($contact)){
			$contact = new Contact;
			$contact->id_record = $id_record;
			$contact->id_user = \Config::get('user.id_user');
		}

		$to_set = array_intersect_key($fields, array_flip($contact->fillable));

		if(empty($to_set['value'])){
			return ['status' => 'failed', 'message' => 'Value is Required'];
		}
		
		$contact->fill($to_set);
		$contact->save();

		Contact\Event::event($contact, ['type' => 'contact.created', 'value' => 'created']);

		return ['status' => 'success', 'contact' => $contact];
	}
	public function deleteContactAction(){
		$id_contact = \Request::get('id_contact');
		$id_record = \Request::get('id_record');

		$contact = Contact::where([
			'id_contact' => $id_contact,
			'id_record' => $id_record,
		])->first();

		if(empty($contact)){
			return ['status' => 'failed', 'message' => 'Contact Not Found'];
		}

		$contact->touch();
		$contact->deleted = 1;
		$contact->save();

		Contact\Event::contactStatus($contact);

		return ['status' => 'success'];
	}

	public function updateStatusAction(){
		$id_contact = \Request::get('id_contact');
		$id_record = \Request::get('id_record');
		$status = \Request::get('status');

		$contact = Contact::where([
			'id_contact' => $id_contact,
			'id_record' => $id_record,
		])->first();

		if(empty($contact)){
			return ['status' => 'failed', 'message' => 'Contact Not Found'];
		}
		if(!$status){
			return ['status' => 'failed', 'message' => 'Must have valid status to update'];
		}
		$contact->touch();
		$contact->status = $status;
		$contact->save();

		Contact\Event::contactStatus($contact);

		return ['status' => 'success'];
	}
}