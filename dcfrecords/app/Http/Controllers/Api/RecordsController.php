<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;

class RecordsController extends Controller {
	public function getRecordsAction(){
		$post = (object) \Request::all();

		$orderBy = 'DESC';
		$order = 'updated_at';
		$offset = !empty($post->offset) ? $post->offset : 0;
		$limit = !empty($post->limit) ? $post->limit : 10;
		$sort = !empty($post->sort) ? $post->sort : false;
		$show = !empty($post->show) ? $post->show : 'active';
		$owned = !empty($post->owned) ? true : false;

		if($sort && $sort[0] != '-'){
			$orderBy = 'ASC';
			$sort = trim($sort, '-');

			if($sort == 'name'){
				$order = 'name';
			}else if($sort == 'date'){
				$sort = 'created_at';
			}
		}

		$args = [];
		if($show == 'active'){
			$args[] = ['status', '<>', 'complete'];
		}else{
			$args[] = ['status', '=', 'complete'];
		}
		if($owned){
			$args[] = ['id_user', '=', \Config::get('user.id_user')];
		}

		$tableset = Record::where($args)
			->orderBy($order, $orderBy);

		$total = $tableset->count();

		$records = $tableset->offset($offset)
			->limit($limit)
			->get();

		if(!$records->isEmpty()){
			foreach($records as $record){
				$record->buildRelations();
			}
		}
		
		return [
			'status' => 'success', 
			'records' => $records,
			'total' => $total,
			'offset' => $offset,
		];
		
	}

	public function getRecordAction(){
		$id_record = \Request::get('id_record');

		$record = Record::find($id_record);

		if(!$record){
			return ['status' => 'failed', 'message' => 'Could not find Record'];
		}

		$record->buildRelations();

		return ['status' => 'success', 'record' => $record];
	}

	public function saveRecordAction(){
		$id_record = \Request::get('id_record');
		$fields = \Request::get('fields');

		if($id_record){
			$record = Record::find($id_record);
		}

		if(empty($record)){
			$record = new Record;
			$record->id_user = \Config::get('user.id_user');
		}

		$to_set = array_intersect_key($fields, array_flip($record->fillable));

		if(empty($to_set['name'])){
			return ['status' => 'failed', 'message' => 'Name is Required'];
		}

		$record->fill($to_set)->save();
		$record->buildRelations();
		return ['status' => 'success', 'record' => $record];
	}
}